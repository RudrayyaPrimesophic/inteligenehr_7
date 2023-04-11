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

$shift_name = $_REQUEST['shift_name'];
$shift_duration = $_REQUEST['shift_duration'];
$created_by = "Admin";
$shift_date = date("Y-m-d", strtotime($_REQUEST['shift_date']));
$array = array($_REQUEST['start_hour'], $_REQUEST['start_min']);
$st_time = implode(':', $array);
$start_time = $st_time . " " . $_REQUEST['start_ap'];
$array1 = array($_REQUEST['end_hour'], $_REQUEST['end_min']);
$ed_time = implode(':', $array1);
$end_time = $ed_time . " " . $_REQUEST['end_ap'];

$qry = "INSERT INTO `shifts_table`(`shift`, `duration`, `created_by`, `created_date`, `start_time`, `end_time`) VALUES ('$shift_name','$shift_duration','$created_by','$shift_date','$start_time','$end_time')";

$res = sqlStatement($qry);
if ($res != "") {
    include('sceduled_rounds.php');
} else {
    echo ("<script>alert('Something Went Wrong Try Again Later Or Contact Adminstrator');</script>");
    include('sceduled_rounds.php');
}
// print_r($query->queryString);
// $query->debugDumpParams();
