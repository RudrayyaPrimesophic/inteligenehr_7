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

$floor_id = $_REQUEST['delete_ward_id'];

$qry = "DELETE FROM `bms_wards` WHERE `id`=$floor_id";

$res = sqlStatement($qry);

if ($res != "") {
    header('Location: BMS_edit.php');
    exit();
} else {
    echo ("<script>alert('Something Went Wrong Try Again Later Or Contact Adminstrator');</script>");
    header('Location: BMS_edit.php');
    exit();
}
