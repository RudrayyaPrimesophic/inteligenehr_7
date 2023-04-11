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

$shift_id = $_REQUEST['shift_id'];
$assigned_to = $_REQUEST['assigned_to'];
$round_leader = $_REQUEST['round_leader'];
$house_building = $_REQUEST['house_building'];
$assigned_by = $_REQUEST['assigned_by'];
$assigned_date = $_REQUEST['assigned_date'];


$qry = "INSERT INTO `shift_assign`(`shift_id`, `assigned_to`, `round_leader`, `house_building`, `assigned_by`, `assigned_date`) VALUES ('$shift_id','$assigned_to','$round_leader','$house_building','$assigned_by','$assigned_date')";

$res = sqlStatement($qry);

if ($res != "") {
    include('sceduled_rounds.php');
} else {
    echo ("<script>alert('Something Went Wrong Try Again Later Or Contact Adminstrator');</script>");
    include('sceduled_rounds.php');
}
