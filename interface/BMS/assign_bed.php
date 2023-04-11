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

$patient_name = $_REQUEST['patient_name'];
$floor_no = $_REQUEST['floor_id'];
$ward_name = $_REQUEST['ward_id'];
$bed_no = $_REQUEST['bed_no'];
$admission_date = $_REQUEST['admission_date'];


$qry = "INSERT INTO `bms`(`id`, `pid`, `floor_no`, `ward_name`, `bed_no`, `admited_date`, `discharge_date`, `created_at`, `updated_at`) VALUES (NULL,'$patient_name','$floor_no','$ward_name','$bed_no','$admission_date',NULL,NULL,NULL)";
$res = sqlStatement($qry);

if ($res != "") {
    header('Location: bed_allocation.php');
    exit();
} else {
    echo ("<script>alert('Something Went Wrong Try Again Later Or Contact Adminstrator');</script>");
    header('Location: bed_allocation.php');
    exit();
}
