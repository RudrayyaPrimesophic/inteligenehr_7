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
// require_once("config.php");

use OpenEMR\Common\Csrf\CsrfUtils;
//use OpenEMR\Core\Header;
//use OpenEMR\OeUI\OemrUI;

?>
<html>

<head>
    <link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css"> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script> -->
    <link rel="stylesheet" href="<?= $webroot ?>/public/themes/dashboard.css">
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= $webroot ?>/public/themes/rounds.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery-datetimepicker/build/jquery.datetimepicker.min.css" type="text/css">
    <script src="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery-datetimepicker/build/jquery.datetimepicker.full.min.js"></script>
    <title>Shifts</title>

    <?php
    $date = date('l F j, Y, g:i a T', time());
    $time = date('g:i A', time());
    $time1 = strtotime($time);
    ?>

</head>

<body>
    <?php $authuser_id = $_SESSION['authUserID']; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="page-header">
                    <h2 id="header_tag">Scheduled Rounds</h2>
                    <h5>Today <?= $date; ?></h5>
                </div>
            </div>
            <div class="col-7 pt-2">
                <div class="row">
                    <div class="btn-group mx-2">
                        <a href="past_rounds.php" class="button">View Past Rounds</a>
                    </div>
                    <?php
                    // if (acl_check('admin', 'super')) { 
                    ?>
                    <div class="btn-group mx-2">
                        <button class="button_model " data-toggle="modal" data-target=".bd-example-modal-xl"><i class="fa fa-plus" aria-hidden="true"></i> Add New Shift</button>
                    </div>
                    <div class="btn-group mx-2">
                        <button class="button_model" data-toggle="modal" data-target=".assign_shift"><i class="fa fa-plus" aria-hidden="true"></i> Assign Shift</button>
                    </div>
                    <?php
                    //  }
                    ?>
                    <div class="btn-group mx-2">
                        <a href="assigned_shift.php" class="button">Assigned Shift</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            $query = "SELECT * FROM `shifts_table` WHERE `created_date`=CURRENT_DATE";
            $res = sqlStatement($query);
            ?>
            <div class="col-md-12">
                <table id="myTable" class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Round</th>
                            <th>Status</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Interval</th>
                            <th>Leader</th>
                            <th>Assigned To</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($res as $row) {
                            $shift_id = $row['id'];
                            $query1 = "SELECT * FROM `shift_assign` JOIN `users` ON `users`.`id`=`shift_assign`.`assigned_to` WHERE `shift_assign`.`shift_id`='$shift_id'";
                            $res1 = sqlStatement($query1);
                            $row1 = sqlFetchArray($res1);
                        ?>
                            <tr>
                                <td><?= $row['shift']; ?></td>
                                <td>
                                    <?php
                                    $sttime = strtotime($row['start_time']);
                                    $edtime = strtotime($row['end_time']);
                                    if ($time1 >= $sttime && $time1 <= $edtime) {
                                        echo "Inprogress";
                                    } elseif ($time1 > $edtime) {
                                        echo "Completed";
                                    } else {
                                        echo "Upcoming";
                                    }
                                    ?>
                                </td>
                                <td><?= $row['start_time']; ?></td>
                                <td><?= $row['end_time']; ?></td>
                                <td><?= $row['duration']; ?> Minutes</td>
                                <td><?= $row['created_by']; ?></td>
                                <td>
                                    <?= $row1['fname']  ?> <?= $row1['mname']  ?> <?= $row1['lname']  ?>
                                </td>
                                <td><a href="<?= $webroot ?>/interface/rounds/rounds.php?shift_id=<?= $row['id']; ?>" class="button">view</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
</body>
<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="ob_modal_popup">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myExtraLargeModalLabel">Add New Shifts</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= $webroot ?>/interface/rounds/insert_shifts.php" method="post">
                <div class="modal-body" style="height: 300px;">
                    <div class="row">
                        <div class="col-md-4 pt-2">
                            <span> Shift Name : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="text" class="form-control" name="shift_name" id="" required>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> Interval : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="number" class="form-control" placeholder="Please Enter in Minutes" name="shift_duration" id="" required>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> Date : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="text" class="form-control date-picker" name="shift_date" id="" autocomplete="off" required>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> Start Time : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <div class="row">
                                <div class="col-4">
                                    <select name="start_hour" id="" class="form-control" required>
                                        <?php
                                        for ($i = 01; $i <= 12; $i++) {
                                            printf("<option value='%02d'>", $i);
                                            printf("%02d</option>", $i);
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select name="start_min" id="" class="form-control" required>
                                        <?php
                                        for ($i = 00; $i <= 59; $i++) {
                                            printf("<option value='%02d'>", $i);
                                            printf("%02d</option>", $i);
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select name="start_ap" id="" class="form-control" required>
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> End Time : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <div class="row">
                                <div class="col-4">
                                    <select name="end_hour" id="" class="form-control" required>
                                        <?php
                                        for ($i = 01; $i <= 12; $i++) {
                                            printf("<option value='%02d'>", $i);
                                            printf("%02d</option>", $i);
                                        }
                                        ?>
                                        <!-- <option value=" <?= $i; ?>"><?php echo $k; ?></option> -->
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select name="end_min" id="" class="form-control" required>
                                        <?php
                                        for ($i = 00; $i <= 59; $i++) {
                                            printf("<option value='%02d'>", $i);
                                            printf("%02d</option>", $i);
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select name="end_ap" id="" class="form-control" required>
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                                </div>
                            </div>
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
<div class="modal fade assign_shift " tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="ob_modal_popup">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myExtraLargeModalLabel">Assign Shifts</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= $webroot ?>/interface/rounds/assign_shifts.php" method="post">
                <div class="modal-body" style="height: 300px;">
                    <div class="row">
                        <div class="col-md-4 pt-2">
                            <span> Shift : </span>
                        </div>
                        <?php
                        $query = "SELECT * FROM `shifts_table` WHERE `created_date`=CURRENT_DATE";
                        $res = sqlStatement($query);
                        ?>
                        <div class="col-md-8 pt-2">
                            <select name="shift_id" id="" class="form-control" required>
                                <option value="">Select Shift</option>
                                <?php foreach ($res as $row) { ?>
                                    <option value="<?= $row['id'] ?>"><?= $row['shift'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php
                        $query_rl = "SELECT * FROM `users`";
                        $res_rl = sqlStatement($query_rl);
                        ?>
                        <div class="col-md-4 pt-2">
                            <span> Assigned To : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <select class="form-control" name="assigned_to" id="">
                                <option value="">Please Assign Shift To</option>
                                <?php foreach ($res_rl as $row_rl) { ?>
                                    <option value="<?= $row_rl['id']; ?>"><?= $row_rl['fname']; ?> <?= $row_rl['mname']; ?> <?= $row_rl['lname']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> Round Leader : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <select class="form-control" name="round_leader" id="">
                                <option value="">Please Select Round Leader</option>
                                <?php foreach ($res_rl as $row_rl) { ?>
                                    <option value="<?= $row_rl['id']; ?>"><?= $row_rl['fname']; ?> <?= $row_rl['mname']; ?> <?= $row_rl['lname']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> House Building : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <?php
                            $query = "SELECT `title` FROM `list_options` WHERE `list_id` ='house_building'";
                            $res = sqlStatement($query);
                            ?>
                            <select class="form-control" name="house_building" id="">
                                <option value="">Please Select House Building</option>
                                <?php foreach ($res as $row) { ?>
                                    <option value="<?= $row['title']; ?>"><?= $row['title']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php
                        $query_ab = "SELECT * FROM `users` WHERE `id`=$authuser_id";
                        $res_ab = sqlStatement($query_ab);
                        $row_ab = sqlFetchArray($res_ab);
                        ?>
                        <div class="col-md-4 pt-2">
                            <span> Assigned By : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="text" class="form-control " name="assigned_by" id="" value="<?= $authuser_id; ?>" hidden readonly required>
                            <input type="text" class="form-control " name="" id="" value="<?= $row_ab['fname']; ?> <?= $row_ab['mname']; ?> <?= $row_ab['lname']; ?>" readonly required>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> Assigned date : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="text" class="form-control date-picker" name="assigned_date" id="" autocomplete="off" required>
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
<script>
    $(function() {
        $('.date-picker').datetimepicker({
            format: 'MM/DD/YYYY',
            <?php $datetimepicker_timepicker = false; ?>
            <?php $datetimepicker_showseconds = false; ?>
            <?php $datetimepicker_formatInput = true; ?>
            <?php require($GLOBALS['srcdir'] . '/js/xl/jquery-datetimepicker-2-5-4.js.php'); ?>
            <?php // can add any additional javascript settings to datetimepicker here; need to prepend first setting with a comma 
            ?>
        });
    });
</script>


</html>