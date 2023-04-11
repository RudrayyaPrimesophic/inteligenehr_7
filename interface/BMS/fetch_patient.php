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

$lvl_of_care = $_POST['lvl_of_care'];


$query = "SELECT * FROM patient_data WHERE lvl_care=$lvl_of_care AND admission_date != '' AND (discharge_date ='' OR discharge_date is NULL)";
$res = sqlStatement($query);

$form_data = '<select name="patient_name" id="patient_name" class="form-control" required><option value="">Select Patient Name</option>';
foreach ($res as $row) {
    $id = $row['pid'];
    $query_bms = "SELECT *,count(*) as count FROM `bms` WHERE `pid`='$id' AND `discharge_date` IS NULL";
    $res_bms = sqlStatement($query_bms);
    $row_bms = sqlFetchArray($res_bms);
    if ($row_bms['count'] > 0) {
    } else {
        $form_data .= '<option value="' . $row['pid'] . '">' . $row['fname'] . ' ' . $row['lname'] . '</option>';
    }
}
$form_data .= '</select>';


echo $form_data;

// json_encode