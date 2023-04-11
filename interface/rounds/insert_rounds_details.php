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
require_once("../../sites/default/sqlconf.php");

use OpenEMR\Common\Csrf\CsrfUtils;
//use OpenEMR\Core\Header;
//use OpenEMR\OeUI\OemrUI;

if (!empty($_REQUEST['current_interval'])) {
    $round_id = $_REQUEST['round_id'];
    $shift_id = $_REQUEST['shift_id'];
    $pid = $_REQUEST['pid'];
    $status = $_REQUEST['patients_status'];
    $location = $_REQUEST['patients_location'];
    $date = $_REQUEST['entry_time'];
    $observed_by = $_REQUEST['observed_by'];
    $current_interval = $_REQUEST['current_interval'];
    $interval_date = $_REQUEST['interval_date'];

    $qry1 = "INSERT INTO `patient_rounds`(`round_id`, `shift_id`,`nursing_note_id`, `pid`, `vitals_id`,`status`, `location`, `last_observed`, `observed_by`,`interval_time`,`interval_date`) VALUES ('$round_id','$shift_id','NULL','$pid','NULL','$status','$location','$last_observed','$observed_by','$current_interval','$interval_date')";

    $res1 = sqlStatement($qry1);

    if ($res1 != '') {
        // print_r($query1->queryString);
        // $query1->debugDumpParams();
        include('rounds.php');
    } else {
        echo ("<script>alert('Something Went Wrong Try Again Later Or Contact Adminstrator');</script>");
        include('rounds.php');
    }
} else {
    $round_id = $_REQUEST['round_id'];
    $shift_id = $_REQUEST['shift_id'];
    $pid = $_REQUEST['pid'];
    echo ("<script>alert('Please Select Round Interval');</script>");
    include('rounds.php');
}
