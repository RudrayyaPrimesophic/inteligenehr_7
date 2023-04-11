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

$id = $_REQUEST['ward_id_update'];
$ward_name = $_REQUEST['ward_name_update'];
$level_of_care = $_REQUEST['level_of_care_update'];
$no_of_beds = $_REQUEST['no_of_beds_update'];
$starting_from = $_REQUEST['starting_from_update'];
$ending_at = $starting_from + $no_of_beds - 1;

$qry = "UPDATE `bms_wards` SET `ward_name`='$ward_name',`level_of_care`='$level_of_care',`no_of_beds`='$no_of_beds',`bed_ending_at`='$ending_at',`updated_at`=CURRENT_TIMESTAMP WHERE `id`=$id";

$res = sqlStatement($qry);

if ($res != "") {
    header('Location: BMS_edit.php');
    exit();
} else {
    echo ("<script>alert('Something Went Wrong Try Again Later Or Contact Adminstrator');</script>");
    header('Location: BMS_edit.php');
    exit();
}
