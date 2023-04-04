<?php

/**
 * Edit demographics.
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Brady Miller <brady.g.miller@gmail.com>
 * @copyright Copyright (c) 2017 Brady Miller <brady.g.miller@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

use Mpdf\Mpdf;

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/erx_javascript.inc.php");
require_once("$srcdir/validation/LBF_Validation.php");
require_once("$srcdir/patientvalidation.inc.php");
require_once("$srcdir/pid.inc");
require_once("$srcdir/patient.inc");

$prescriptions = explode(',', $_GET['prescriptions_ids']);
$header_id = $_GET['header_id'];

$providers = [];

$sql = 'SELECT id, fname, lname FROM users';
$res = sqlStatement($sql);
while ($row = sqlFetchArray($res)) {
    $providers[$row['id']] = $row;
}

$query_pid = "SELECT * FROM `patient_data` WHERE `pid`='$pid'";
$res_pid = sqlStatement($query_pid);
$patient_details = sqlFetchArray($res_pid);

$sql = "SELECT patient_data.pid,patient_data.fname,patient_data.mname,patient_data.lname,patient_data.DOB,patient_data.sex,patient_data.admission_date,patient_data.facility_id,facility.name,insurance_data.provider,insurance_data.plan_name,insurance_data.policy_number,insurance_data.group_number  FROM patient_data LEFT JOIN insurance_data ON patient_data.pid=insurance_data.pid JOIN facility ON facility.id=patient_data.facility_id WHERE patient_data.pid='$pid'";
$res_pd = sqlStatement($sql);
$rez = sqlFetchArray($res_pd);
// $rez = sqlQuery($sql, array($pid));
$provider = $rez['provider'];
$sql2 = "SELECT insurance_companies.name FROM insurance_companies WHERE insurance_companies.id='$provider'";
$res_pd2 = sqlStatement($sql2);
$rez2 = sqlFetchArray($res_pd2);

$facility_id = $rez['facility_id'];
$query_facility = "SELECT * FROM `facility` WHERE id='$facility_id'";
$res_facility = sqlStatement($query_facility);
$facility_details = sqlFetchArray($res_facility);


$admission_date = ($rez['admission_date'] != '') ? date('d-m-Y', strtotime($rez['admission_date'])) : '';

// ob_clean();
// header('Content-type: application/pdf');
// header('Content-Disposition: inline; filename="mPdf"');
// header('Content-Transfer-Encoding: binary');
// header('Accept-Ranges: bytes');

$html = '<table width="100%">
                    <tr>
                        <th style="text-align: left;">
                            <div class="row" >
                                <div class="col-md-12" style="font-weight: bold;font-size: 12px;">
                                    ' . $rez['fname'] . ' ' . $rez['mname'] . ' ' . $rez['lname'] . '
                                </div>
                                <div class="col-md-12" style="font-weight: normal;font-size: 10px;">
                                    Patient ID - ' . $rez['pid'] . ' 
                                </div>
                                <div class="col-md-12" style="font-weight: normal;font-size: 10px;">
                                    ' . date('d-m-Y', strtotime($rez['DOB'])) . ' , ' . $rez['sex'] . '
                                </div>
                                <div class="col-md-12" style="font-weight: normal;font-size: 10px;">
                                    ' . $rez['name'] . '
                                </div>
                                <div class="col-md-12" style="font-weight: normal;font-size: 10px;">
                                    Admission Date -' .  $admission_date . '
                                </div>
                                <div class="col-md-12" style="font-weight: normal;font-size: 10px;">
                                    Insurance Provider -' . $rez2['name'] . '
                                </div>
                                <div class="col-md-12" style="font-weight: normal;font-size: 10px;">
                                    Plan -' . $rez['plan_name'] . ' ,Policy # -' . $rez['policy_number'] . '
                                </div>
                                <div class="col-md-12" style="font-weight: normal;font-size: 10px;">
                                    Group ID -' . $rez['group_number'] . ' 
                                </div>
                            </div>
                        </th>
                        <th><img class="logo oe-pull-toward" alt="openEMR small logo" style="width:auto;height:75px" border="0" src="' . $GLOBALS['images_static_relative'] . '/logo-full-con.png">
                        <br>
                        <span>Verbal orders for ' . $patient_details['fname'] . ' ' . $patient_details['lname'] . '</span>
                        </th>
                        
                        <th style="text-align: right;">
                            <div class="row">
                                <div class="col-md-12" style="font-weight: bold;font-size: 12px;">
                                    Psyclarity Health Massachusetts
                                </div>
                                <div class="col-md-12" style="font-weight: normal;font-size: 12px;">
                                    163 Hamilton St
                                </div>
                                <div class="col-md-12" style="font-weight: normal;font-size: 12px;">
                                    Saugus,MA 01906
                                </div>
                                <div class="col-md-12" style="font-weight: normal;font-size: 12px;">
                                    United States
                                </div>
                                <div class="col-md-12" style="font-weight: normal;font-size: 12px;">
                                    (855) 920-5310
                                </div>
                            </div>
                        </th>
                    </tr>
                 </table>
                 <hr>';
$html .= '<table width="100%" style="border: 1px solid black;">
<thead>
                    <tr style="border: 1px solid black;">
                        <th style="border: 1px solid black;width:30%">Drug</th>
                        <th style="border: 1px solid black;width:15%">Start Date</th>
                        <th style="border: 1px solid black;width:10%">Med Time</th>
                        <th style="border: 1px solid black;width:15%">End Date</th>
                        <th style="border: 1px solid black;width:10%">Meds Status</th>
                        <th style="border: 1px solid black;width:20%">Updated On</th>
                    </tr>
                    </thead>

        ';
foreach ($prescriptions as $prescription_id) {

    $query = "SELECT prescriptions.id,prescriptions.is_prn,prescriptions.provider_id,prescriptions.active,prescriptions.verbal ,patient_data.fname, patient_data.lname, `date_added`, `drug`,`quantity`, `med_brought_in_id`, prescriptions.`date_modified`,prescriptions.`updated_by`,(SELECT title FROM `list_options` where list_id = 'drug_form'and option_id = `form` and activity = 1) as drug_form, `dosage`, `continue_on_discharge`,`size`, (SELECT title FROM `list_options` where list_id = 'drug_units'and option_id = `unit` and activity = 1) as drug_units, (SELECT title FROM `list_options` where list_id = 'drug_route'and option_id = `route` and activity = 1) as drug_route, (SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `instruction` and activity = 1) as drug_instruction, (SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `interval` and activity = 1) as drug_interval, `datetime`, `start_date` , `note`,(SELECT max(med_time) FROM `med_logs` WHERE prescription_id = prescriptions.`id`) as enddate,(SELECT GROUP_CONCAT(med_time, ', ') FROM `med_logs` WHERE prescription_id = prescriptions.`id` GROUP BY prescription_id) as medtime from prescriptions, patient_data where prescriptions.`id`='" . $prescription_id . "'and patient_data.pid=prescriptions.patient_id and patient_data.pid='" . $pid . "' ORDER BY start_date ASC";
    $res = sqlStatement($query);
    $developer = sqlFetchArray($res);

    $html .= '<tr style="border: 1px solid black;">
                        <td style="border: 1px solid black;">
                            <h6>' . $developer['drug'] . '</h6>
                            ' . $developer['dosage'] . '-' . $developer['drug_form'] . ' ,' . $developer['drug_route'] . ' ,' . $developer['drug_interval'] . ' - <strong style="font-size:13px;">' . $developer['size'] . ' ' . $developer['drug_units'] . '</strong><br>';
    if ($developer['is_prn'] || $developer['interval'] == 17 || $developer['interval'] == 18) :
        $html .= '<p class="px-3 py-1 mt-2" style="color:darkblue;"> (P.R.N) When Necessary</p>';
    endif;
    $html .= '<p class="prov-note mb-0"><span class="provider_name">' . $providers[$developer['provider_id']]['fname'] . ' '
        . $providers[$developer['provider_id']]['lname'] . ' </span> :<span class="p-2" style="background:#F5EDDC; color: darkblue;">' . $developer['note'] . '</span></p><span class="date_added_class">' . date("m-d-Y H:i:s", strtotime($developer['date_added'])) . '</span></td>';
    $html .= '<td style="border: 1px solid black;">' . date("m-d-Y", strtotime($developer['start_date'])) . '</td>

                        <td style="border: 1px solid black;">';

    $med_time_arr = explode(',', $developer['medtime']);
    foreach ($med_time_arr as $med_time_data) {
        if (strlen($med_time_data) > 5) {
            $html .= '' . date("H:i:s", strtotime($med_time_data)) . '<br>';
        } else {
            $html .= '';
        }
    }

    $html .= '</td>
    <td style="border: 1px solid black;">';
    if ($developer['enddate']) {
        $html .= '' . date("m-d-Y", strtotime($developer['enddate'])) . '';;
    } else {
        $html .= '';
    }
    $html .= '</td><td style="border: 1px solid black;">';
    if ($developer['active'] == 1) {
        $html .= 'Active';
    } else {
        $html .= 'Inactive';
    }
    $html .= '</td><td style="border: 1px solid black;">';
    if ($developer['date_modified']) {
        $html .= '' . date("m-d-Y H:i:s", strtotime($developer['date_modified'])) . '';
    } else {
        $html .= '';
    }
    $html .= '<br><span class="provider_name">' . $providers[$developer['updated_by']]['fname'] . ' ' . $providers[$developer['updated_by']]['lname'] . '</span> </td></tr>';

    $drug = $developer['drug'];
    if (strposa($developer['drug'], $medicine_list)) :
        $html .= '<tr><td colspan="11" class="text-center text-danger"><h5>Perform AIMS</h5></td></tr>';
    endif;
}

$qry_sign = "SELECT * FROM `medlogs_signature_header` WHERE header_id='$header_id'";
$res_sign = sqlStatement($qry_sign);
$med_logs_sign = sqlFetchArray($res_sign);
$staffid = $med_logs_sign["staff_id"];
$qry_user = "SELECT * FROM `users` WHERE id='$staffid'";
$res_user = sqlStatement($qry_user);
$med_logs_user = sqlFetchArray($res_user);

$html .= '</table> 
<div class="row" style="margin-top:10px">
<div class="col-md-12">
<span style="font-size:18px">Approved and Signed by</span>
</div>
<div class="col-md-6">
<h4>' . $med_logs_user['fname'] . ' ' . $med_logs_user['lname'] . ' </h4>
</div>
<div class="col-md-6">
<span>(' . $med_logs_sign['created_at'] . ')</span>
</div>

</div>
';

// echo $html;

$path = 'sites/default/documents/' . $pid . '/' . $pid . '-' . date("Ymdhis") . '.pdf';
$sql_u = "UPDATE `medlogs_signature_header` SET `file_location`='$path' WHERE `header_id`='$header_id'";
sqlStatement($sql_u);

$config_mpdf = array(
    'tempDir' => $GLOBALS['MPDF_WRITE_DIR'],
    'mode' => $GLOBALS['pdf_language'],
    'format' => $GLOBALS['pdf_size'],
    'default_font_size' => '',
    'default_font' => '',
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 15,
    'margin_bottom' => 15,
    'margin_header' => '',
    'margin_footer' => '',
    'orientation' => $GLOBALS['pdf_layout'],
    'shrink_tables_to_fit' => 1,
    'use_kwt' => true,
    'autoScriptToLang' => true,
    'keep_table_proportions' => true
);
$pdf = new mPDF($config_mpdf);

// $pdf = new mPDF();
$pdf->writeHTML($html);
ob_clean();
if ($pdf->Output('/var/www/vhosts/psyclarityehr.csardent.com/httpdocs/' . $path, 'F')) {
    // log_user_event('eMAR - Genrate Meds Pdf', 'Genrated Meds Pdf For :' . $pid, $_SESSION['authUserID']);
    $data = ['status' => true];
} else {
    // log_user_event('eMAR - Genrate Meds Pdf', 'Failed to Genrate Meds Pdf For :' . $pid, $_SESSION['authUserID']);
    $data = ['status' => false];
}


// D = Download, I = Inline
// $pdf->Output();
// if ($pdf->Output()) {
//     $data = ['status' => true];
// } else {
//     $data = ['status' => false];
// }
echo json_encode($data);
