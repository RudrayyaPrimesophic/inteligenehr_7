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
use Zend\Db\Sql\Ddl\Column\Timestamp;

//use OpenEMR\Core\Header;
//use OpenEMR\OeUI\OemrUI;

$ward_name = $_REQUEST['ward_name'];
$floor_no = $_REQUEST['floor_no'];
$level_of_care = $_REQUEST['level_of_care'];
$no_of_beds = $_REQUEST['no_of_beds'];
$starting_from = $_REQUEST['starting_from'];
$ending_at = $starting_from + $no_of_beds - 1;

$qry = "INSERT INTO `bms_wards`(`id`, `ward_name`, `floor_no`, `level_of_care`, `no_of_beds`,`beds_starting_from`, `bed_ending_at`, `created_at`, `updated_at`) VALUES (NULL,'$ward_name','$floor_no','$level_of_care','$no_of_beds','$starting_from','$ending_at',NULL,NULL)";

$res = sqlStatement($qry);

if ($res != "") {
    header('Location: BMS_edit.php');
    exit();
} else {
    echo ("<script>alert('Something Went Wrong Try Again Later Or Contact Adminstrator');</script>");
    header('Location: BMS_edit.php');
    exit();
}
