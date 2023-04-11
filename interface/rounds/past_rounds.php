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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="<?= $webroot ?>/public/themes/dashboard.css">
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= $webroot ?>/public/themes/rounds.css">
    <link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery-datetimepicker/build/jquery.datetimepicker.min.css" type="text/css">
    <script src="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery-datetimepicker/build/jquery.datetimepicker.full.min.js"></script>
    <title>Past Shifts</title>
    <style>
        #header_tag {
            font-size: 24px;
        }

        .page-header {
            border-bottom: 0px !important;
        }

        button {
            background-color: #06576a;
            border-color: #06576a;
        }
    </style>
    <?php
    $date = date('l F j, Y, g:i a T', time());
    ?>

</head>

<body>
    <div class="container-fluid">
        <div class="row pt-2 pb-2">
            <div class="col-2">
                <a href="sceduled_rounds.php" class="button"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> View Rounds </a>
            </div>
        </div>
        <div class="row">
            <form action="past_rounds.php" method="post">
                <div class="row">
                    <div class="col-md-4">
                        <div class="col-12">Start Date</div>
                        <div class="col-12 input-group">
                            <input type="text" class="form-control date-picker" name="st_date" autocomplete="OFF" required>
                            <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-12">End Date</div>
                        <div class="col-12 input-group">
                            <input type="text" class="form-control date-picker" name="ed_date" autocomplete="OFF" required>
                            <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="col-12" style="color: transparent">.</div>
                        <button class="search_past_rounds">Go</button>
                    </div>
                </div>
            </form>
            <br><br><br><br>
            <?php
            if (isset($_POST['st_date']) && isset($_POST['ed_date'])) {
                $start_date = date("Y-m-d", strtotime($_POST['st_date']));
                $end_date = date("Y-m-d", strtotime($_POST['ed_date']));
                $query = "SELECT * FROM `shifts_table` WHERE `created_date`>='" . $start_date . "' AND `created_date` <= '" . $end_date . "'ORDER BY `id` DESC";
                $res = sqlStatement($query);
            } else {
                $query = "SELECT * FROM `shifts_table` WHERE `created_date` < CURRENT_DATE ORDER BY `id` DESC";
                $res = sqlStatement($query);
            }
            ?>
            <div class="col-md-12">
                <table id="myTable" class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Shift</th>
                            <th>Status</th>
                            <th>Round Duration</th>
                            <th>Created By</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($res as $row) { ?>
                            <tr>
                                <td><?= date("m-d-Y", strtotime($row['created_date'])); ?></td>
                                <td><?= $row['start_time']; ?></td>
                                <td><?= $row['end_time']; ?></td>
                                <td><?= $row['shift']; ?></td>
                                <td>
                                    <?php
                                    echo "Completed";
                                    ?>
                                </td>
                                <td><?= $row['duration']; ?> Minutes</td>
                                <td><?= $row['created_by']; ?></td>
                                <td><a href="rounds.php?shift_id=<?= $row['id']; ?>" class="button">view</a></td>
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

        $(function() {
            $('.date-picker').datetimepicker({
                <?php $datetimepicker_timepicker = false; ?>
                <?php $datetimepicker_showseconds = false; ?>
                <?php $datetimepicker_formatInput = false; ?>
                <?php require($GLOBALS['srcdir'] . '/js/xl/jquery-datetimepicker-2-5-4.js.php'); ?>
                <?php // can add any additional javascript settings to datetimepicker here; need to prepend first setting with a comma 
                ?>
            });
        });
    </script>
</body>

</html>