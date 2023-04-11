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

$round_id = '1';
$status = $_REQUEST['status'];
$location = $_REQUEST['location'];
$observed_by = $_REQUEST['observed_by'];

$current_interval = $_REQUEST['current_interval'];
$interval_date = $_REQUEST['interval_date'];
$shift_id = $_REQUEST['shift_id'];

$date = $_REQUEST['entry_time'];
$pid = $_REQUEST['pid'];

$user = $_SESSION['authUserID'];

//form_vitals details
$bp_systolic = $_REQUEST['bp_systolic'];
$bp_diastolic = $_REQUEST['bp_diastolic'];
$temprature = $_REQUEST['temprature'];
$pulse = $_REQUEST['pulse'];
$respiration = $_REQUEST['respiration'];
$o2_saturation = $_REQUEST['o2_saturation'];
$reading = $_REQUEST['reading'];
$intervention = $_REQUEST['Intervention'];
$patient_weight = $_REQUEST['patient_weight'];
$notes = $_REQUEST['notes'];
$date1 = date("Y-m-d H:i:s");

// nursing notes details

$neuro_assessment = $_REQUEST['neuro_assessment'];
$respiratory = $_REQUEST['respiratory'];
$appetite = $_REQUEST['appetite'];
$gastrointestinal = $_REQUEST['gastrointestinal'];
$musculoskeletal = $_REQUEST['musculoskeletal'];
$mental_health = $_REQUEST['mental_health'];
$complaints_of_pain = $_REQUEST['complaints_of_pain'];
$suicide_thoughts = $_REQUEST['suicide_thoughts'];
$supportive_counseling = $_REQUEST['supportive_counseling'];
if ($_REQUEST['nursing_interventions_initiated'] == 'Yes') {
    $nursing_interventions_initiated = $_REQUEST['nursing_interventions_initiated'];
} else {
    $nursing_interventions_initiated = 'NO';
}
$compliant_with = $_REQUEST['compliant_with'];

$qry = "INSERT INTO `form_vitals`(`date`, `pid`, `user`, `groupname`, `authorized`, `activity`, `bps`, `bpd`, `weight`, `height`, `temperature`, `temp_method`, `pulse`, `respiration`, `note`, `BMI`, `BMI_status`, `waist_circ`, `head_circ`, `oxygen_saturation`, `glucose`, `external_id`, `intervention`,`reading`) VALUES ('$date1','$pid','$user','NULL','NULL','NULL','$bp_systolic','$bp_diastolic','$patient_weight','NULL','$temprature','NULL','$pulse','$respiration','$notes','$BMI','NULL','NULL','NULL','$o2_saturation','$reading','NULL','$intervention','$reading')";

$res = sqlStatement($qry);

if ($res != "") {
    $qry_fetch = "SELECT `id` FROM `form_vitals` where id=(SELECT LAST_INSERT_ID())";
    $res_fetch = sqlStatement($qry_fetch);
    $row_fetch = sqlFetchArray($res_fetch);

    $vitals_id = $row_fetch['id'];

    $qry_nursing = "INSERT INTO `nursing_note`(`pid`,`shift_id`, `datetime`, `neuro_assessment`, `respiratory`, `appetite`, `gastrointestinal`, `musculoskeletal`, `complaints_of_pain`, `mental_health`, `suicide_thoughts`, `compliant_with`, `nursing_interventions_initiated`, `supportive_counseling`) VALUES ('$pid','$shift_id','$date1','$neuro_assessment','$respiratory','$appetite','$gastrointestinal','$musculoskeletal','$complaints_of_pain','$mental_health','$suicide_thoughts','$compliant_with','$nursing_interventions_initiated','$supportive_counseling')";

    $res_nursing = sqlStatement($qry_nursing);

    if ($res_nursing != "") {

        $qry_fetch_nursing = "SELECT `id` FROM `nursing_note` where id=(SELECT LAST_INSERT_ID())";
        $res_fetch_nursing = sqlStatement($qry_fetch_nursing);
        $row_fetch_nursing = sqlFetchArray($res_fetch_nursing);

        $nursing_id = $row_fetch_nursing['id'];

        $qry1 = "INSERT INTO `patient_rounds`(`round_id`, `shift_id`,`nursing_note_id`,`pid`, `vitals_id`, `status`, `location`, `last_observed`, `observed_by`,`interval_time`,`interval_date`) VALUES ('$round_id','$shift_id','$nursing_id','$pid','$vitals_id','$status','$location','$date','$observed_by','$current_interval','$interval_date')";

        $res1 = sqlStatement($qry1);

        if ($res1 != "") {
            // print_r($query1->queryString);
            // $query1->debugDumpParams();
            include('rounds.php');
        } else {
            echo ("<script>alert('Something Went Wrong Try Again Later Or Contact Adminstrator');</script>");
            include('rounds.php');
        }
    } else {
        echo ("<script>alert('Something Went Wrong Try Again Later Or Contact Adminstrator');</script>");
        include('rounds.php');
    }
} else {
    echo ("<script>alert('Something Went Wrong Try Again Later Or Contact Adminstrator');</script>");
    include('rounds.php');
}
