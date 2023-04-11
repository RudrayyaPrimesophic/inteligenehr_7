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

?>
<html>

<head>
    <link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" href="<?= $webroot ?>/public/themes/dashboard.css">
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery-datetimepicker/build/jquery.datetimepicker.full.min.js"></script>
    <link rel="stylesheet" href="<?= $webroot ?>/public/themes/rounds.css">
    <title>Rounds</title>

    <?php
    $date = date('l F j, Y', time());
    ?>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <?php

                    if (isset($_GET['shift_id'])) {
                        $id = $_GET['shift_id'];
                    } elseif (isset($_REQUEST['shift_id'])) {
                        $id = $_REQUEST['shift_id'];
                    } else {
                        $id = $shift_id;
                    }

                    if (isset($_REQUEST['current_interval'])) {
                        $current_interval = $_REQUEST['current_interval'];
                    } else {
                        $current_interval = $current_interval;
                    }

                    $query = "SELECT * FROM `shifts_table` WHERE `id`=$id";
                    $res = sqlStatement($query);
                    $row = sqlFetchArray($res);
                    $created_date = $row['created_date'];
                    ?>
                    <h2 id="header_tag">Every <?= $row['duration'] ?> Minutes</h2>
                    <h5>Today <?= $date; ?> From <?= $row['start_time'] ?> to <?= $row['end_time'] ?></h5>
                    <a href="<?= $webroot ?>/interface/rounds/sceduled_rounds.php" class="button"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Rounds</a>
                    <?php
                    $shift_id = $row['id'];
                    $query_shift = "SELECT * FROM `shift_assign` Join `users` ON `shift_assign`.`round_leader`=`users`.`id` WHERE `shift_assign`.`shift_id`=$shift_id";
                    $res_shift = sqlStatement($query_shift);
                    $row_shift = sqlFetchArray($res_shift);
                    ?>
                    <p>Round leader : <?= $row_shift['fname']; ?> <?= $row_shift['mname']; ?> <?= $row_shift['lname']; ?></p>
                    <p>Building : <?= $row_shift['house_building']; ?></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <form action="rounds.php" id="interval_form" method="post">
                            <input type="text" name="shift_id" id="" value="<?= $id ?>" readonly hidden>
                            <div class="row">
                                <div class="col-md-3">Current Interval :</div>
                                <div class="col-md-4">
                                    <?php
                                    if (isset($_GET['current_interval'])) {
                                        echo $_GET['current_interval'];
                                    }
                                    $sttime = strtotime($row['start_time']);
                                    $edtime = strtotime($row['end_time']);
                                    $diff = $edtime - $sttime;
                                    $total_hours = $diff / 3600;
                                    $total_shifts = $total_hours * 60 / $row['duration'];

                                    $time = date('g:i A', time());
                                    $time1 = strtotime($time);

                                    $pd_count_query = "SELECT count(*) as count FROM patient_data pat WHERE admission_date is not null and admission_date != '' and (pat.discharge_date is null OR pat.discharge_date = '' OR pat.discharge_date > CURRENT_DATE)";

                                    $pd_count_res = sqlStatement($pd_count_query);
                                    $pd_count_row = sqlFetchArray($pd_count_res);
                                    $count_pd = $pd_count_row['count'];

                                    ?>
                                    <select class="form-control" name="current_interval" id="current_interval">
                                        <?php if (isset($_REQUEST['current_interval'])) { ?>
                                            <option value="<?= $current_interval ?>" selected><?= $current_interval ?> (Selected)</option>
                                        <?php } else { ?>
                                            <option value="">Select Interval</option>
                                        <?php } ?>
                                        <?php for ($i = 0; $i < $total_shifts; $i++) {
                                            $isttime = date('h:i a', strtotime('+' . $row['duration'] * $i . 'minutes ', strtotime($row['start_time'])));
                                            $iedtime = date('h:i a', strtotime('+' . $row['duration'] * ($i + 1) . 'minutes ', strtotime($row['start_time'])));

                                            $cpc_current_interval = $isttime . "-" . $iedtime;

                                            $check_patient_c_qry = "SELECT count(*) as count FROM patient_rounds WHERE interval_date='$created_date' AND interval_time = '$cpc_current_interval'";
                                            $cpc_res = sqlStatement($check_patient_c_qry);
                                            $cpc_row = sqlFetchArray($cpc_res);
                                            $date = date('Y-m-d');
                                            if ($created_date < $date) {
                                                if ($count_pd == $cpc_row['count']) {
                                                    $process = "(Complete)";
                                                } elseif ($cpc_row['count'] > 0) {
                                                    $process = "(Incomplete)";
                                                } else {
                                                    $process = "(Not Started)";
                                                }
                                            } else {
                                                $istt = strtotime($isttime);
                                                $iedt = strtotime($iedtime);
                                                if ($time1 >= $istt && $time1 <= $iedt) {
                                                    $process = "(Inprogress)";
                                                } elseif ($time1 > $iedt) {
                                                    if ($count_pd == $cpc_row['count']) {
                                                        $process = "(Complete)";
                                                    } elseif ($cpc_row['count'] > 0) {
                                                        $process = "(Incomplete)";
                                                    } else {
                                                        $process = "(Not Started)";
                                                    }
                                                } else {
                                                    $process = "(Upcoming)";
                                                }
                                            }

                                            if ($cpc_current_interval != $current_interval) {
                                        ?>
                                                <option value="<?= $cpc_current_interval ?>"><?= $cpc_current_interval ?> <?= $process ?> </option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <!-- <div class="col-md-3">
                                <input type="search" class="form-control" name="" id="" placeholder="Filter">
                            </div> -->
                            <!-- <div class="col-md-3 next_interval">
                                <span>Next Interval In : 10 min</span>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <b> Patient</b>
            </div>
            <div class="col-md-3">
                <b> Status</b>
            </div>
            <div class="col-md-4">
                <b> Location</b>
            </div>
        </div>
        <hr>
        <div class="patient_details">
            <?php
            $pd_query = "SELECT * FROM patient_data pat WHERE admission_date is not null and admission_date != '' and (pat.discharge_date is null OR pat.discharge_date = '' OR pat.discharge_date > CURRENT_DATE)";
            $pd_res = sqlStatement($pd_query);

            foreach ($pd_res as $pd_row) {
                $pid = $pd_row['pid'];

                $check_patient_qry = "SELECT * FROM patient_rounds WHERE interval_date='$created_date' AND interval_time = '$current_interval' AND pid='$pid'";
                $cp_res = sqlStatement($check_patient_qry);
                $cp_row = sqlFetchArray($cp_res);
                if (empty($cp_row)) {
            ?>

                    <form action="insert_rounds_details.php" method="post" enctype="multipart/form-data">
                        <input type="text" name="current_interval" id="" value="<?= $current_interval; ?>" hidden readonly>

                        <input type="text" name="interval_date" id="" value="<?= $created_date; ?>" hidden readonly>

                        <input type="text" name="pid" id="" value="<?= $pd_row['pid']; ?>" hidden readonly>
                        <input type="text" name="shift_id" id="" value="<?= $id; ?>" hidden readonly>
                        <input type="text" name="round_id" id="" value="1" hidden readonly>

                        <input type="text" name="observed_by" id="" value="<?= $row_shift['assigned_to']; ?>" readonly hidden>

                        <input type="text" name="entry_time" id="" value="<?= date('m/d/Y h:i a', time()); ?>" hidden readonly>
                        <div class="row">
                            <div class="col-md-1">
                                <?php
                                // If there is an ID Card or any Photos show the widget
                                $photos = pic_array($pd_row['pid'], $GLOBALS['patient_photo_category_name']);
                                $src = $webroot . "/public/images/patient-picture-default-big.jpg";
                                if (count($photos)) {
                                    $src = image_widget($pd_row['pid'], $photos[0], $GLOBALS['patient_photo_category_name']);
                                }
                                ?>
                                <img src="<?= $src ?>" alt="<?= $pd_row['fname'] ?> <?= $pd_row['lname'] ?>" title="<?= $pd_row['fname'] ?> <?= $pd_row['lname'] ?>" class="img-fluid profile">
                            </div>
                            <div class="col-md-3 patient_name">
                                <h4><?= $pd_row['fname'] ?> <?= $pd_row['lname'] ?></h4>
                            </div>
                            <div class="col-md-3">
                                <?php
                                $query = "SELECT `title` FROM `list_options` WHERE `list_id` ='patient_rounds_status'";
                                $res = sqlStatement($query);
                                ?>

                                <select class="form-control" name="patients_status" id="">
                                    <option value="">Please Select Patient Status</option>
                                    <?php foreach ($res as $row) { ?>
                                        <option value="<?= $row['title']; ?>"><?= $row['title']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <?php
                                $query = "SELECT `title` FROM `list_options` WHERE `list_id` ='patient_rounds_location'";
                                $res = sqlStatement($query);
                                ?>
                                <select class="form-control" name="patients_location" id="">
                                    <option value="">Please Select Patient Location</option>
                                    <?php foreach ($res as $row) { ?>
                                        <option value="<?= $row['title']; ?>"><?= $row['title']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="btn_group mx-2">
                                <?php if ($_SESSION['authUserID'] == $row_shift['assigned_to'] or acl_check('admin', 'super')) { ?>
                                    <button type="submit" class="observe_btn">Observe</button>
                                <?php } ?>
                            </div>
                            <div class="btn_group mx-2">
                                <?php if ($_SESSION['authUserID'] == $row_shift['assigned_to'] or acl_check('admin', 'super')) { ?>
                                    <button type="button" class="modal_popup_btn" data-toggle="modal" data-target=".bd-example-modal-xl" data-pid="<?= $pd_row['pid']; ?>" data-img=<?= $src; ?> data-fname=<?= $pd_row['fname']; ?> data-lname=<?= $pd_row['lname']; ?> style="padding:10px;">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                    <br>
            <?php }
            } ?>
        </div>
        <hr>

        <div class="completed_patient_details">
            <?php
            $pd_query = "SELECT * FROM patient_data pat WHERE admission_date is not null and admission_date != '' and (pat.discharge_date is null OR pat.discharge_date = '' OR pat.discharge_date > CURRENT_DATE)";
            $pd_res = sqlStatement($pd_query);
            $int_time = explode('-', $current_interval);
            $shift_time_st_con = $row['created_date'] . " " . $int_time[0];
            $shift_st_time = date("m/d/Y h:i a", strtotime($shift_time_st_con));

            $shift_time_ed_con = $row['created_date'] . " " . $int_time[1];
            $shift_ed_time = date("m/d/Y h:i a", strtotime($shift_time_ed_con));
            foreach ($pd_res as $pd_row) {
                $pid = $pd_row['pid'];

                $check_patient_qry = "SELECT * FROM patient_rounds WHERE interval_date='$created_date' AND interval_time = '$current_interval' AND pid='$pid'";
                $cp_res = sqlStatement($check_patient_qry);
                $cp_row = sqlFetchArray($cp_res);
                if (!empty($cp_row)) {
            ?>
                    <div class="row">
                        <div class="col-md-1">
                            <?php
                            // If there is an ID Card or any Photos show the widget
                            $photos = pic_array($pd_row['pid'], $GLOBALS['patient_photo_category_name']);
                            $src = $webroot . "/public/images/patient-picture-default-big.jpg";
                            if (count($photos)) {
                                $src = image_widget($pd_row['pid'], $photos[0], $GLOBALS['patient_photo_category_name']);
                            }
                            ?>
                            <img src="<?= $src ?>" alt="<?= $pd_row['fname'] ?> <?= $pd_row['lname'] ?>" title="<?= $pd_row['fname'] ?> <?= $pd_row['lname'] ?>" class="img-fluid profile">
                        </div>
                        <div class="col-md-3 patient_name">
                            <h4><?= $pd_row['fname'] ?> <?= $pd_row['lname'] ?></h4>
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" value="<?= $cp_row['status']; ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" value="<?= $cp_row['location']; ?>" readonly>
                        </div>
                        <div class="btn_group mx-2">
                            <button type="button" class="observe_btn" style="background-color: grey;">Observed</button>
                        </div>
                        <div class="btn_group mx-2">
                            <button type="button" class="pv_details_popup " data-pid="<?= $pd_row['pid']; ?>" data-p_round_id="<?= $cp_row['id']; ?>" data-vitals_id="<?= $cp_row['vitals_id']; ?>" style="background-color: grey;padding:10px;">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <br>
            <?php }
            } ?>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>

    <!-- Modal -->
    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="ob_modal_popup">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myExtraLargeModalLabel" style="color: #06576a;">Observation Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= $webroot ?>/interface/rounds/insert_patient_data.php" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-2">
                                <img src="" id="img_src" alt="Intelligen EHR">
                            </div>
                            <div class="col-md-4 patient_name p-0 pl-3">
                                <h4 id="name"></h4>
                            </div>
                            <input type="text" name="current_interval" id="" value="<?= $current_interval; ?>" hidden readonly>
                            <input type="text" name="interval_date" id="" value="<?= $created_date; ?>" hidden readonly>
                            <input type="text" name="pid" id="pid" hidden>
                            <input type="text" name="shift_id" id="" value="<?= $id; ?>" hidden readonly>
                            <input type="text" name="observed_by" id="" value="<?= $row_shift['assigned_to']; ?>" readonly hidden>
                            <div class="col-md-3">
                                <div class="col-12 p-0">
                                    <h6>Patient Status</h6>
                                </div>
                                <div class="col-12 p-0">
                                    <?php
                                    $query = "SELECT `title` FROM `list_options` WHERE `list_id` ='patient_rounds_status'";
                                    $res = sqlStatement($query);
                                    ?>
                                    <select class="form-control" name="status" id="">
                                        <option value="">Please Select Patient Status</option>
                                        <?php foreach ($res as $row) { ?>
                                            <option value="<?= $row['title']; ?>"><?= $row['title']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="col-12 p-0">
                                    <h6>Patient Location</h6>
                                </div>
                                <div class="col-12 p-0">
                                    <?php
                                    $query = "SELECT `title` FROM `list_options` WHERE `list_id` ='patient_rounds_location'";
                                    $res = sqlStatement($query);
                                    ?>
                                    <select class="form-control" name="location" id="">
                                        <option value="">Please Select Patient Location</option>
                                        <?php foreach ($res as $row) { ?>
                                            <option value="<?= $row['title']; ?>"><?= $row['title']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 vitals_details">
                                <span>Vitals</span> <input type="checkbox" name="vitals" id="vitals"> No Entry
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="" value="<?= date('m/d/Y h:i a', time()); ?>" id="" readonly>
                                <input type="text" class="form-control" name="entry_time" value="<?= date('m/d/Y h:i a', time()); ?>" id="" hidden readonly>
                            </div>
                            <div class="col-md-12" id="vitals_display">
                                <div class="row">
                                    <div class="col-md-2">
                                        BP Systolic
                                    </div>
                                    <div class="col-md-2">
                                        BP Diastolic
                                    </div>
                                    <div class="col-md-2">
                                        Temperature
                                    </div>
                                    <div class="col-md-2">
                                        Pulse
                                    </div>
                                    <div class="col-md-2">
                                        Respiration
                                    </div>
                                    <div class="col-md-2">
                                        O2 Saturation
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" name="bp_systolic" id="">
                                    </div>
                                    <div class=" col-md-2">
                                        <input type="text" class="form-control" name="bp_diastolic" id="">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" name="temprature" id="">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" name="pulse" id="">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" name="respiration" id="">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" name="o2_saturation" id="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="col-md-12 vitals_details">
                                    <span>Glucose</span> <input type="checkbox" name="" id="glucose"> No Entry
                                </div>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="" value="<?= date('m/d/Y h:i a', time()); ?>" id="" readonly>
                                </div>
                                <div class="col-md-12" id="glucose_display">
                                    <div class="col-md-12">
                                        <div class="col-12">
                                            Reading*
                                        </div>
                                        <div class="col-12">
                                            <input type="text" class="form-control" name="reading" value="" id="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <p>Intervention*</p> <input type="radio" name="Intervention" value="none" id=""> None
                                    </div>
                                    <div class="col-md-12">
                                        <input type="radio" name="Intervention" value="Medicated as Ordered" id=""> Medicated as Ordered
                                    </div>
                                    <div class="col-md-12">
                                        <input type="radio" name="Intervention" value="Check in 1 hour" id=""> Check in 1 hour
                                    </div>
                                    <div class="col-md-12">
                                        <input type="radio" name="Intervention" value="Check in 2 hours" id=""> Check in 2 hours
                                    </div>
                                    <div class="col-md-12">
                                        <input type="radio" name="Intervention" value="Physician Called" id=""> Physician Called
                                    </div>
                                </div>
                            </div>
                            <div id="weight_style" class="col-md-4">
                                <span>Weight</span> <input type="checkbox" name="weight" id="weight"> No Entry
                                <div class="col-12">
                                    <input type="text" class="form-control" name="" value="<?= date('m/d/Y h:i a', time()); ?>" id="" readonly>
                                </div>
                                <div id="weight_display">
                                    <div class="col-12">
                                        weight in lbs
                                    </div>
                                    <div class="col-12">
                                        <input type="text" class="form-control" name="patient_weight" value="" id="">
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="col-md-4">
                                <div class="col-12">
                                    Type of Check *
                                </div>
                                <div class="col-12">
                                    <select class="form-control" name="" id="">
                                        <option value=""></option>
                                        <option value=""></option>
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div> -->

                            <div class="col-md-12">
                                <div class="col-md-3">
                                    Notes
                                </div>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="notes" id="">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12 vitals_details p-0">
                            <span style="color: #06576a;">Nursing Notes</span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                Neuro Assessment
                            </div>
                            <div class="col-md-6">
                                Respiratory
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="neuro_assessment" id="">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="respiratory" id="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                Appetite
                            </div>
                            <div class="col-md-6">
                                Gastrointestinal
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="appetite" id="">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="gastrointestinal" id="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                Musculoskeletal
                            </div>
                            <div class="col-md-6">
                                Mental Health
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="musculoskeletal" id="">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="mental_health" id="">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12" style="text-shadow: 0 0 black;">
                                Pain Scale
                            </div>
                            <div class="col-md-12">
                                <input type="range" name="pain_scale" min="0" max="10" class="slider" id="myRange" value="0">
                                <div class="row" style="padding-left:15px;padding-top: 10px;">
                                    <p>Value: <span id="range_demo"></span></p>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                Complaints of Pain
                            </div>
                            <div class="col-md-12">
                                <textarea name="complaints_of_pain" id="" class="form-control" cols="30" rows="30" style="height: 100px !important;"></textarea>
                            </div>
                        </div>

                        <div class="row pt-3">
                            <div class="col-md-12">
                                Suicide Thoughts
                            </div>
                            <div class="col-md-6">
                                <input type="radio" name="suicide_thoughts" id="" value="Currently Reports Suicidal thoughts or ideations">Currently Reports Suicidal thoughts or ideations
                            </div>
                            <div class="col-md-6 p-0">
                                <input type="radio" name="suicide_thoughts" id="" value="Denies any Current Suicidal thoughts or ideations">Denies any Current Suicidal thoughts or ideations
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-md-12">
                                Supportive Counseling
                            </div>
                            <div class="col-md-12">
                                <textarea name="supportive_counseling" id="" class="form-control" cols="30" rows="30" style="height: 100px !important;"></textarea>
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-md-12">
                                Compliant with
                            </div>
                            <div class="col-md-12">
                                <textarea name="compliant_with" id="" class="form-control" cols="30" rows="30" style="height: 100px !important;"></textarea>
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-md-12">
                                <input type="Checkbox" name="nursing_interventions_initiated" id="" value="Yes"> Nursing Interventions Initiated
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="id_popup">

    </div>
    <script>
        $(function() {
            $('.datepicker').datetimepicker({
                format: 'MM/DD/YYYY',
                <?php $datetimepicker_timepicker = true; ?>
                <?php $datetimepicker_showseconds = true; ?>
                <?php $datetimepicker_formatInput = true; ?>
                <?php require($GLOBALS['srcdir'] . '/js/xl/jquery-datetimepicker-2-5-4.js.php'); ?>
                <?php  ?>
                <?php // can add any additional javascript settings to datetimepicker here; need to prepend first setting with a comma 
                ?>
            });
        });

        $(document).on("click", ".modal_popup_btn", function() {
            var fname = $(this).data('fname');
            var lname = $(this).data('lname');
            var name = fname.concat(" ", lname);
            var pid = $(this).data('pid');
            var src = $(this).data('img');
            $("#name").html(name);
            document.getElementById('img_src').src = src;
            $("#img_src").val(src);
            $("#pid").val(pid);
            $("current_interval").val(current_interval);

        });

        $('#vitals').click(function() {
            if ($('#vitals').is(":checked")) {
                document.getElementById('vitals_display').style.display = "none";
            } else {
                document.getElementById('vitals_display').style.display = "block";
            }
        });
        $('#glucose').click(function() {
            if ($('#glucose').is(":checked")) {
                document.getElementById('glucose_display').style.display = "none";
            } else {
                document.getElementById('glucose_display').style.display = "block";
            }
        });
        $('#weight').click(function() {
            if ($('#weight').is(":checked")) {
                document.getElementById('weight_display').style.display = "none";
            } else {
                document.getElementById('weight_display').style.display = "block";
            }
        });
        $(document).ready(function() {
            $('#current_interval').change(function() {
                $value = document.getElementById('current_interval').value;
                if ($value != "") {
                    document.getElementById('interval_form').submit();
                } else {
                    alert("Please Select Correct Interval");
                }
            });

            $('.pv_details_popup').click(function() {
                var pid = $(this).data('pid');
                var patient_round_id = $(this).data('p_round_id');
                var vitals_id = $(this).data('vitals_id');
                if (vitals_id != "") {
                    $("#id_popup").html('');
                    $.ajax({
                        url: "fetch_model.php",
                        type: "post",
                        data: {
                            pid: pid,
                            patient_round_id: patient_round_id,
                            vitals_id: vitals_id
                        },
                        success: function(response) {
                            $('#id_popup').html(response);
                            $('#pd_modal_popup').modal('toggle');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
                } else {
                    alert("Vitals Data NoT Recorded");
                }
            });

        });

        var slider = document.getElementById("myRange");
        var output = document.getElementById("range_demo");
        output.innerHTML = slider.value; // Display the default slider value
        // Update the current slider value (each time you drag the slider handle)
        slider.oninput = function() {
            output.innerHTML = this.value;
        }
    </script>
</body>

</html>