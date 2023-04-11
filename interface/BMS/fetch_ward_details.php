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

$floor_no = $_POST['floor_no'];
$sql = "SELECT * FROM `bms_wards` WHERE `floor_no`='$floor_no'";
$res = sqlStatement($sql);

$form_data = '<select name="ward_no" id="ward_no" class="form-control" required><option value="">Select Room Number</option>';
foreach ($res as $row) {
    $form_data .= '<option value="' . $row['id'] . '">' . $row['ward_name'] . '</option>';
}
$form_data .= '</select>';


echo $form_data;
