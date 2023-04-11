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

$ward_name = $_POST['ward_name'];
$bed_array = [1 => 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'Z'];

$sql = "SELECT * FROM `bms_wards` WHERE `id`='$ward_name'";
$res = sqlStatement($sql);
$row = sqlFetchArray($res);

$start = $row['beds_starting_from'];
$end = $row['bed_ending_at'];
$ward_id = $row['id'];

$form_data = '<select name="bed_no" id="bed_no" class="form-control" required><option value="">Select Bed</option>';
for ($i = $start; $i <= $end; $i++) {
    $query_bms = "SELECT count(*) as count FROM `bms` WHERE `ward_name`= '$ward_id' AND `bed_no`='$i' AND `discharge_date` IS NULL";
    $res_bms = sqlStatement($query_bms);
    $row_bms = sqlFetchArray($res_bms);
    if ($row_bms['count'] > 0) {
    } else {
        $bed_name = (($i - $start + 1) < 27) ? $bed_array[$i - $start + 1] : $i;
        $form_data .= '<option value="' . $i . '">' . $bed_name . '</option>';
    }
}
$form_data .= '</select>';

echo $form_data;
