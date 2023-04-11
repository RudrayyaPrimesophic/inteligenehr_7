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
// require_once("$srcdir/acl.inc");

use OpenEMR\Common\Csrf\CsrfUtils;

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
    <link rel="stylesheet" href="<?= $webroot ?>/public/themes/rounds.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="<?= $webroot ?>/interface/BMS/bms.css?v=1.1">
    <script src="<?= $webroot ?>/interface/BMS/bms.js?v=1"></script>
    <title>BMS</title>
</head>

<body>
    <?php
    $date = date('l F j, Y, g:i a T', time());
    $time = date('g:i A', time());
    $time1 = strtotime($time);
    ?>
    <div class="container-fluid">
        <div class="row mt-2">
            <div class="col-md-5">
                <div class="page-header m-0">
                    <h2 id="header_tag">Bed Management System</h2>
                    <h5>Today <?= $date; ?></h5>
                </div>
            </div>
        </div>

        <div class="row bg-bms">
            <br>
            <?php
            $bed_array = [1 => 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'Z'];
            $query = "SELECT * FROM `bms_floor` ORDER BY `floor_no` ASC";
            $res = sqlStatement($query);
            foreach ($res as $row) {
                $floor_no = $row['floor_no'];
                $floor_id = $row['id'];
                $no_of_wards = $row['no_of_wards'];

            ?>
                <div class="col-md-12">
                    <div class="card bed_card">
                        <div class="card-header">
                            <div class="floor_name">
                                <span class="h5 mb-0"><?= $row['floor_name'] ?></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row ward">
                                <?php
                                $query_ward = "SELECT * FROM `bms_wards` WHERE `floor_no`='$floor_id' ORDER BY `beds_starting_from` ASC";
                                $res_ward = sqlStatement($query_ward);
                                foreach ($res_ward as $ward_row) {
                                    $start = $ward_row['beds_starting_from'];
                                    $end = $ward_row['bed_ending_at'];
                                    $level_of_care = $ward_row['level_of_care'];
                                    $ward_id = $ward_row['id'];

                                    $query_loc = "SELECT * FROM `list_options` WHERE `list_id`='level_of_care_list' and `option_id`='$level_of_care'";
                                    $res_loc = sqlStatement($query_loc);
                                    $row_loc = sqlFetchArray($res_loc);

                                ?>
                                    <div class="col-md-12 ward_name">
                                        <h5><?= $ward_row['ward_name'] ?></h5>
                                    </div>
                                    <div class="row bed-assign_row">
                                        <?php for ($i = $start; $i <= $end; $i++) {
                                            $query_bms = "SELECT *,count(*) as count FROM `bms` WHERE `ward_name`= '$ward_id' AND `bed_no`='$i' AND `discharge_date` IS NULL";
                                            $res_bms = sqlStatement($query_bms);
                                            $row_bms = sqlFetchArray($res_bms);
                                            if ($row_bms['count'] > 0) {
                                                $pid = $row_bms['pid'];
                                                $query_patient = "SELECT * FROM `patient_data` WHERE `pid`='$pid'";
                                                $res_patient = sqlStatement($query_patient);
                                                $row_patient = sqlFetchArray($res_patient);
                                                if ($row_patient['sex'] == "Male") {
                                                    $gender = 'male';
                                                } elseif ($row_patient['sex'] == "Female") {
                                                    $gender = 'female';
                                                } elseif ($row_patient['sex'] == "Other") {
                                                    $gender = 'other';
                                                } else {
                                                    $gender = 'lgbtq';
                                                }
                                        ?>
                                                <div class="col-md-2 beds">
                                                    <a href="javascript:void(0)" class="reassign_beds <?= $gender ?>" data-id="<?= $row_bms['id']; ?>" data-tooltip data-tooltip-message="Bed No: <?= (($row_bms['bed_no'] - $start + 1) < 27 ) ? $bed_array[$row_bms['bed_no'] - $start + 1] :$row_bms['bed_no'] ; ?>&#10;Patient Id : <?= $row_bms['pid']; ?>&#10;Admission Date : <?= $row_bms['admited_date']; ?>&#10;Phone No. : <?= $row_patient['phone_contact']; ?>&#10;Gender : <?= $row_patient['sex']; ?>&#10;Guardian Name : <?= $row_patient['']; ?>&#10;Consultant : <?= $row_patient['']; ?>">
                                                        <i class=" fa-solid fa-bed"></i>
                                                        <p><?= $row_patient['fname'] ?> <?= $row_patient['lname'] ?></p>
                                                    </a>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-md-2 beds">
                                                    <a href="javascript:void(0)" class="assign_beds" data-floor_no="<?= $floor_no ?>" data-ward_name="<?= $ward_row['ward_name'] ?>" data-bed_no="<?= $i ?>" data-bed_no_text="<?= (($i - $start + 1) < 27 ) ? $bed_array[$i - $start + 1] : $i; ?>" data-lvl_of_care="<?= $ward_row['level_of_care'] ?>" data-floor_id="<?= $row['id']; ?>" data-ward_id="<?= $ward_row['id']; ?>">
                                                        <i class="fa-solid fa-bed"></i>
                                                        <p><?= (($i - $start + 1) < 27 ) ? $bed_array[$i - $start + 1] : $i; ?></p>
                                                    </a>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>

<!-- Assign Bed  -->
<div class="modal fade" id="assign_beds" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myExtraLargeModalLabel">Add BMS Room Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= $webroot ?>/interface/BMS/assign_bed.php" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 pt-2">
                            <span> Patient Name : </span>
                        </div>
                        <div class="col-md-8 pt-2" id="patient_find">

                        </div>
                        <div class="col-md-4 pt-2">
                            <span> Floor No. : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="number" name="floor_no" id="floor_no" class="form-control" readonly required>
                            <input type="hidden" name="floor_id" id="floor_id">
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> Room Name. : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="text" name="ward_name" id="ward_name" class="form-control" readonly required>
                            <input type="hidden" name="ward_id" id="ward_id">
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> Bed No. : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="text" name="bed_no_text" id="bed_no_text" class="form-control" readonly required>
                            <input type="hidden" name="bed_no" id="bed_no" class="form-control" readonly required>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> Bed allocation date : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="date" name="admission_date" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- By Rudrayya For Reassign Of Bed 16-09-2022 Start -->
<!-- Discharge Bed -->
<div class="modal fade" id="discharge_update" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myExtraLargeModalLabel">Patient Bed Management</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="mt-2 ml-2" id="buttons">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-secondary active" id="discharge_btn">Release Bed</button>
                    <button type="button" class="btn btn-secondary" id="reassign_btn">Change Bed</button>
                </div>
            </div>
            <div class="d-block" id="discharge_section">
                <form action="<?= $webroot ?>/interface/BMS/discahrge_patient.php" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id" id="id">
                            <div class="col-md-4 pt-2">
                                <span> Discharge Date : </span>
                            </div>
                            <div class="col-md-8 pt-2">
                                <input type="date" class="form-control" name="discharge_date" id="" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Release</button>
                    </div>
                </form>
            </div>
            <div class="d-none" id="reassign_section">
                <form action="<?= $webroot ?>/interface/BMS/reassign_patient.php" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id" id="reassign_id">
                            <div class="col-md-3 pt-2">
                                <span> Change Bed: </span>
                            </div>
                            <div class="col-md-3 pt-2">
                                <select name="floor_no" id="reassign_patient_foor" class="form-control" required>
                                    <option value="">Select Floor Number</option>
                                    <?php
                                    $query = "SELECT * FROM `bms_floor`";
                                    $res = sqlStatement($query);
                                    foreach ($res as $row) {
                                    ?>
                                        <option value="<?= $row['id']; ?>"><?= $row['floor_name']; ?></option>
                                    <?php
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-3 pt-2" id="room_no_div">

                            </div>
                            <div class="col-md-3 pt-2" id="beds_no_div">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- By Rudrayya For Reassign Of Bed 16-09-2022 End -->



</html>