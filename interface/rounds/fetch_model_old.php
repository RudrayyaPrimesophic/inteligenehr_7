<?php

/**
 * POST-NUKE Content Management System
 * Based on:
 * PHP-NUKE Web Portal System - http://phpnuke.org/
 * Thatware - http://thatware.org/
 *
 * Purpose of this file: Directs to the start page as defined in config.php
 *
 * @author    Francisco Burzi
 * @author    Post-Nuke Development Team
 * @author    Brady Miller <brady.g.miller@gmail.com>
 * @copyright Copyright (c) 2001 by the Post-Nuke Development Team <http://www.postnuke.com/>
 * @copyright Copyright (c) 2019 Brady Miller <brady.g.miller@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */
require_once("../globals.php");
require_once "$srcdir/user.inc";
require_once "$srcdir/options.inc.php";
// require_once("config.php");

use OpenEMR\Common\Csrf\CsrfUtils;
//use OpenEMR\Core\Header;
//use OpenEMR\OeUI\OemrUI;

// get an array from Photos category
function pic_array($pid, $picture_directory)
{
    $pics = array();
    $sql_query = "select documents.id from documents join categories_to_documents " .
        "on documents.id = categories_to_documents.document_id " .
        "join categories on categories.id = categories_to_documents.category_id " .
        "where categories.name like ? and documents.foreign_id = ?";
    if ($query = sqlStatement($sql_query, array($picture_directory, $pid))) {
        while ($results = sqlFetchArray($query)) {
            array_push($pics, $results['id']);
        }
    }

    return ($pics);
}

function image_widget($pid, $doc_id, $doc_catg)
{
    global $web_root;
    $docobj = new Document($doc_id);
    $image_file = $docobj->get_url_file();
    $image_width = $GLOBALS['generate_doc_thumb'] == 1 ? '' : 'width=100';
    $extension = substr($image_file, strrpos($image_file, "."));
    $viewable_types = array('.png', '.jpg', '.jpeg', '.png', '.bmp', '.PNG', '.JPG', '.JPEG', '.PNG', '.BMP');
    if (in_array($extension, $viewable_types)) { // extension matches list
        $to_url = "$web_root" . "/controller.php?document&retrieve&patient_id=" . attr_url($pid) . "&document_id=" . attr_url($doc_id) . "&as_file=false";
    }

    return $to_url;
}

$pid = $_POST['pid'];
$patient_round_id = $_POST['patient_round_id'];
$vitals_id = $_POST['vitals_id'];

$pd_query = "SELECT * FROM patient_data WHERE pid='$pid'";
$pd_res = sqlStatement($pd_query);
$pd_row = sqlFetchArray($pd_res);

$check_patient_qry = "SELECT * FROM patient_rounds WHERE id='$patient_round_id' AND pid='$pid'";
$cp_res = sqlStatement($check_patient_qry);
$cp_row = sqlFetchArray($cp_res);

$vitals_patient_qry = "SELECT * FROM form_vitals WHERE id='$vitals_id' AND pid='$pid'";
$vl_res = sqlStatement($vitals_patient_qry);
$vl_row = sqlFetchArray($vl_res);

$nursing_note_id = $cp_row['nursing_note_id'];
$nursing_patient_qry = "SELECT * FROM nursing_note WHERE id='$nursing_note_id' AND pid='$pid'";
$nn_res = sqlStatement($nursing_patient_qry);
$nn_row = sqlFetchArray($nn_res);

if ($nn_row['nursing_interventions_initiated'] == 'Yes') {
    $nii = '<input type="Checkbox" name="nursing_interventions_initiated" id="" readonly style="pointer-events: none;" checked> Nursing Interventions Initiated';
} else {
    $nii = '<input type="Checkbox" name="nursing_interventions_initiated" id="" readonly style="pointer-events: none;"> Nursing Interventions Initiated';
}

// If there is an ID Card or any Photos show the widget
$photos = pic_array($pd_row['pid'], $GLOBALS['patient_photo_category_name']);
$src = $webroot . "/public/images/patient-picture-default-big.jpg";
if (count($photos)) {
    $src = image_widget($pd_row['pid'], $photos[0], $GLOBALS['patient_photo_category_name']);
}

$form_data = '<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="pd_modal_popup"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="myExtraLargeModalLabel">Observation Details</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 0rem; margin: 0rem 0rem 0rem auto;"><span aria-hidden="true">&times;</span></button></div><div class="modal-body"><div class="row"><div class="col-md-2"><img src="' . $src . '" id="" alt="' . $pd_row['fname'] . ' ' . $pd_row['lname'] . '"></div><div class="col-md-3 patient_name"><h4 id="name">' . $pd_row['fname'] . ' ' . $pd_row['lname'] . '</h4></div><div class="col-md-3"><input type="text" class="form-control" value="' . $cp_row['status'] . '" readonly></div><div class="col-md-3"><input type="text" class="form-control" value="' . $cp_row['location'] . '" readonly></div></div><hr><div class="row"><div class="col-md-12 vitals_details"><span>Vitals</span></div><div class="col-md-6"><input type="text" class="form-control" value="' . $cp_row['last_observed'] . '" id="" readonly></div><div class="col-md-12" id="vitals_display"><div class="row"><div class="col-md-2">BP Systolic</div><div class="col-md-2">BP Diastolic</div><div class="col-md-2">Temprature</div><div class="col-md-2">Pulse</div><div class="col-md-2">Respiration</div><div class="col-md-2">O2 Saturation</div></div><div class="row"><div class="col-md-2"><input type="text" class="form-control" value="' . $vl_row['bps'] . '" readonly></div><div class=" col-md-2"><input type="text" class="form-control" value="' . $vl_row['bpd'] . '" readonly></div><div class="col-md-2"><input type="text" class="form-control" value="' . $vl_row['temperature'] . '" readonly></div><div class="col-md-2"><input type="text" class="form-control" value="' . $vl_row['pulse'] . '"  readonly></div><div class="col-md-2"><input type="text" class="form-control" value="' . $vl_row['respiration'] . '"  readonly></div><div class="col-md-2"><input type="text" class="form-control" value="' . $vl_row['oxygen_saturation'] . '" readonly></div></div></div></div><hr><div class="row"><div class="col-md-8"><div class="col-md-12 vitals_details"><span>Glucose</span></div><div class="col-md-12"><input type="text" class="form-control" value="' . $cp_row['last_observed'] . '" id="" readonly></div><div class="col-md-12" id="glucose_display"><div class="col-md-12">Reading*</div><div class="col-md-12"><input type="text" class="form-control" value="' . $vl_row['reading'] . '" readonly></div><div class="col-md-12"><p>Intervention*</p></div><div class="col-md-12"><input type="text" class="form-control" value="' . $vl_row['intervention'] . '" readonly></div></div></div><div id="weight_style" class="col-md-4"><span>weight</span><div class="col-12"><input type="text" class="form-control" value="' . $cp_row['last_observed'] . '" id="" readonly></div><div id="weight_display"><div class="col-12">weight</div><div class="col-12"><input type="text" class="form-control" value="' . $vl_row['weight'] . '" readonly></div></div></div><div class="col-md-12"><div class="col-md-3">Notes</div><div class="col-md-12"><input type="text" class="form-control" value="' . $vl_row['note'] . '" readonly></div></div></div><hr><div class="col-md-12 vitals_details p-0"><span style="color: #06576a;">Nursing Notes</span></div><div class="row"><div class="col-md-6">Neuro Assessment</div><div class="col-md-6">Respiratory</div><div class="col-md-6"><input type="text" class="form-control" name="neuro_assessment" id="" value="' . $nn_row['neuro_assessment'] . '" readonly></div><div class="col-md-6"><input type="text" class="form-control" name="respiratory" id="" value="' . $nn_row['respiratory'] . '" readonly></div></div><div class="row"><div class="col-md-6">Appetite</div><div class="col-md-6">Gastrointestinal</div><div class="col-md-6"><input type="text" class="form-control" name="appetite" id="" value="' . $nn_row['appetite'] . '" readonly></div><div class="col-md-6"><input type="text" class="form-control" name="gastrointestinal" id="" value="' . $nn_row['gastrointestinal'] . '" readonly></div></div><div class="row"><div class="col-md-6">Musculoskeletal</div><div class="col-md-6">Mental Health</div><div class="col-md-6"><input type="text" class="form-control" name="musculoskeletal" id="" value="' . $nn_row['musculoskeletal'] . '" readonly></div><div class="col-md-6"><input type="text" class="form-control" name="mental_health" id="" value="' . $nn_row['mental_health'] . '" readonly></div></div><div class="row"><div class="col-md-12">Complaints of Pain</div><div class="col-md-12"><textarea name="complaints_of_pain" id="" class="form-control" cols="30" rows="30" style="height: 100px !important;" readonly>' . $nn_row['complaints_of_pain'] . '</textarea></div></div><div class="row pt-3"><div class="col-md-12">Suicide Thoughts</div><div class="col-md-12"><input type="text" name="suicide_thoughts" class="form-control" id="" value="' . $nn_row['suicide_thoughts'] . '" readonly></div></div><div class="row pt-2"><div class="col-md-12">Supportive Counseling</div><div class="col-md-12"><textarea name="supportive_counseling" id="" class="form-control" cols="30" rows="30" style="height: 100px !important;" readonly>' . $nn_row['supportive_counseling'] . '</textarea></div></div><div class="row pt-2"><div class="col-md-12">Compliant with</div><div class="col-md-12"><textarea name="compliant_with" id="" class="form-control" cols="30" rows="30" style="height: 100px !important;" readonly>' . $nn_row['compliant_with'] . '</textarea></div></div><div class="row pt-2"><div class="col-md-12">' . $nii . '</div></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></div></div></div></div>';


echo $form_data;
