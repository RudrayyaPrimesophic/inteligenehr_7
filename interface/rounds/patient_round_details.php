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
                    <h5 id="header_tag">Rounds</h5>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <h6>Assigned Rounds</h6>
            </div>
            <div class="col-md-12">
                <table id="myTable" class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Time</th>
                            <th>Interval</th>
                            <th>Days</th>
                            <th>Buildings</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="col-md-12">
                <h6><b>Unassigned Rounds</b></h6>
            </div>
            <div class="col-md-12">
                <h6>No Unassigned Rounds</h6>
            </div>
            <br>
            <div class="col-md-2">
                <div class="col-12" style="color: transparent">.</div>
                <h5>Observation Hx</h5>
            </div>
            <div class="col-md-4">
                <div class="col-12">Start Date</div>
                <div class="col-12 input-group">
                    <input type="text" class="form-control" name="" id="">
                    <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="col-12">End Date</div>
                <div class="col-12 input-group">
                    <input type="text" class="form-control" name="" id="">
                    <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="col-12" style="color: transparent">.</div>
                <button>Go</button>
            </div>
            <div class="col-md-12">
                <table id="myTable1" class="table">
                    <thead>
                        <tr>
                            <th>Round</th>
                            <th>Date</th>
                            <th>Interval</th>
                            <th>Observed By</th>
                            <th>Observed At</th>
                            <th>location</th>
                            <th>Activity</th>
                            <th>Vitals</th>
                            <th>Orthostatic Vitals</th>
                            <th>Glucose</th>
                            <th>Weight</th>
                            <th>Notes</th>
                            <th>Reviewer Signature</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
            $('#myTable1').DataTable();
        });
    </script>
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