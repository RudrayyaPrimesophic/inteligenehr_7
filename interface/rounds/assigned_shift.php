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

    <title>Assigned Shifts</title>
    <?php
    $date = date('l F j, Y', time());
    ?>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10">
                <div class="page-header">
                    <div class="page-header">
                        <h2 id="header_tag">Assigned Shifts</h2>
                        <h5>Today <?= $date; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mt-2">
                <a href="sceduled_rounds.php" class="button"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> View Rounds </a>
            </div>
        </div>
        <?php
        $query = "SELECT * FROM `shifts_table` JOIN `shift_assign` ON `shifts_table`.`id`=`shift_assign`.`shift_id` JOIN `users` ON `shift_assign`.`round_leader`=`users`.`id`";
        $res = sqlStatement($query);
        ?>
        <!-- <div class="col-md-12"> -->
        <table id="myTable" class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Shift</th>
                    <th>Assigned To</th>
                    <th>Round Leader</th>
                    <th>Assigned By</th>
                    <th>House-Building</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($res as $row) {
                    $date = date("m-d-Y", strtotime($row['created_date']));
                    $assigend_to = $row['assigned_to'];
                    $qry_assign_to = "SELECT * FROM `users` WHERE id=$assigend_to";
                    $res_assign_to = sqlStatement($qry_assign_to);
                    $row_assign_to = sqlFetchArray($res_assign_to);

                    $assigend_by = $row['assigned_by'];
                    $qry_assign_by = "SELECT * FROM `users` WHERE id=$assigend_by";
                    $res_assign_by = sqlStatement($qry_assign_by);
                    $row_assign_by = sqlFetchArray($res_assign_by);
                ?>
                    <tr>
                        <td><?= $date; ?></td>
                        <td><?= $row['start_time']; ?></td>
                        <td><?= $row['end_time']; ?></td>
                        <td><?= $row['shift']; ?></td>
                        <td><?= $row_assign_to['fname']; ?> <?= $row_assign_to['mname']; ?> <?= $row_assign_to['lname']; ?></td>
                        <td><?= $row['fname']; ?> <?= $row['mname']; ?> <?= $row['lname']; ?></td>
                        <td><?= $row_assign_by['fname']; ?> <?= $row_assign_by['mname']; ?> <?= $row_assign_by['lname']; ?></td>
                        <td><?= $row['house_building']; ?></td>
                        <td><a href="rounds.php?shift_id=<?= $row['shift_id']; ?>" class="button">view</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <!-- </div> -->
    </div>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>

    <!-- Modal -->
    <script>
        $(function() {
            $('.datepicker').datetimepicker({
                <?php $datetimepicker_timepicker = true; ?>
                <?php $datetimepicker_showseconds = true; ?>
                <?php $datetimepicker_formatInput = false; ?>
                <?php require($GLOBALS['srcdir'] . '/js/xl/jquery-datetimepicker-2-5-4.js.php'); ?>
                <?php // can add any additional javascript settings to datetimepicker here; need to prepend first setting with a comma 
                ?>
            });
        });
    </script>
</body>

</html>