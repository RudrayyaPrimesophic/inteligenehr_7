<?php
// include_once("db_connect.php");
require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");
// require_once("../../library/acl.inc");
require_once("../../library/encounter.inc");

// require_once("../../../../sites/default/sqlconf.php");


use OpenEMR\Common\Csrf\CsrfUtils;
use OpenEMR\Core\Header;
use OpenEMR\Events\BoundFilter;
use OpenEMR\Events\PatientFinder\PatientFinderFilterEvent;
use OpenEMR\Events\PatientFinder\ColumnFilter;

// $patient_discharged = is_patient_discharged($pid);

$patient_discharged = "false";

$csrf_token = CsrfUtils::collectCsrfToken();

$logged_usser = $_SESSION["authUser"];

$orderset_admins = ['flora', 'nwhite@psyclarityhealthma.com', 'administrator'];

$orderset_admin = in_array($logged_usser, $orderset_admins);

$full_access = false;
$disable_add_btns = 'disabled d-none';

// $aro_groups = acl_get_group_titles($logged_usser);
// if (in_array($aro_groups[0], $GLOBALS['full_acl_groups'])) {
//   $full_access = true;
//   $disable_add_btns = '';
// }

$access_to_continue_meds_btn = 0;

if (in_array($_SESSION['authUser'], $GLOBALS['access_to_continue_meds'])) {
  $access_to_continue_meds_btn = 1;
}

$access_approved_by_btn = 0;

if (in_array($_SESSION['authUser'], $GLOBALS['approved_by'])) {
  $access_approved_by_btn = 1;
}

if ($patient_discharged) {
  $access_to_continue_meds_btn = 0;
  $access_approved_by_btn = 0;
  $full_access = false;
  $disable_add_btns = 'disabled d-none';
}

if (isset($_REQUEST['add_check_vital'])) {
  $date = date('Y-m-d H:i:s');
  $pid = $_REQUEST['pid'];
  $start_date = ($_REQUEST['start_date']) ? $_REQUEST['start_date'] : $date;
  $start_date = date('Y-m-d H:i:s', strtotime($start_date));
  $end_date = ($_REQUEST['end_date']) ? $_REQUEST['end_date'] : $date;
  $end_date = date('Y-m-d H:i:s', strtotime($end_date));
  $vital_check = $_REQUEST['vital_check'];
  if ($_REQUEST['vital_check'] == 'other') {
    $vital_check =  $_REQUEST['doctor_vital_check_other'];
  }
  $provider_id = $_REQUEST['provider_id'];
  $verbal_order = $_REQUEST['verbal_order'];

  $frequency     = $_REQUEST['frequency'];
  $note     = $_REQUEST['note'];
  $encounter = $_SESSION['encounter'];

  $qry = "INSERT INTO `doctors_order`(`id`, `date`, `pid`, `user`, `groupname`, `authorized`, `activity`, `vital_check`, `frequency`,`start_date`,`end_date`,`encounter`,`note`,`provider_id`,`verbal_order`) VALUES (NULL,'$date','$pid',NULL,NULL,'0','1','$vital_check','$frequency','$start_date','$end_date','$encounter','$note','$provider_id','$verbal_order')";
  //$res = sqlStatement($qry);
  if ($res = sqlStatement($qry)) {
    log_user_event('eMAR- Header', 'Inserted Data Into doctors_order For Patient -' . $pid, $_SESSION['authUserID']);
  } else {
    log_user_event('eMAR- Header', 'Failed To Insert Data Into doctors_order For Patient -' . $pid, $_SESSION['authUserID']);
  }

  unset($_REQUEST['add_check_vital']);
  header('Location: ' . $_SERVER['PHP_SELF']);
}


if (isset($_POST['add_vitals_data'])) {
  $date = date('Y-m-d H:i:s');
  $date2 = date('m/d/Y');
  $pid = $GLOBALS['pid'];
  $date = ($_POST['date_time']) ? ($_POST['date_time']) : date('Y-m-d H:i:s');
  $date = date('Y-m-d H:i:s', strtotime($date));
  $weight = $_POST['weight'];

  $height = $_POST['height'];
  $bps = $_POST['bps'];
  $bpd = $_POST['bpd'];
  $pulse = $_POST['pulse'];
  $temperature = $_POST['temperature'];
  $respiration = $_POST['respiration'];
  $saturation = $_POST['oxygen_saturation'];
  $bmi = $_POST['BMI'];
  $encounter = $_SESSION['encounter'];

  $form_name = 'Vitals -' . $date2;
  $user = $_SESSION['authUser'];
  $userid = $_SESSION['authUserID'];

  $qry = "INSERT INTO `form_vitals`(`id`, `date`, `pid`, `user`, `groupname`, `authorized`, `activity`,`date_time`, `weight`, `height`, `bps`, `bpd`, `pulse`, `temperature`,`respiration`,`oxygen_saturation`,`BMI`) VALUES (NULL,'$date','$pid',NULL,NULL,'0','1','$date','$weight','$height','$bps','$bpd','$pulse','$temperature','$respiration','$saturation','$bmi')";
  $vitals_id = sqlInsert($qry);
  if ($vitals_id > 0) {
    $qry2 = "INSERT INTO `forms` (`id`, `date`, `encounter`, `form_name`, `form_id`, `pid`, `user`, `groupname`, `authorized`, `deleted`, `formdir`, `therapy_group_id`, `issue_id`, `provider_id`, `is_locked`, `updated`, `updated_by`, `is_revoked`, `revoked_reason`) VALUES (NULL,'$date','$encounter','$form_name','$vitals_id','$pid','$user','Default','1','0','vitals',NULL,'0','0','1','$date','$userid','0',NULL)";
    $form_id = sqlInsert($qry2);
    log_user_event('eMAR- Header', 'Inserted Data Into form_vitals For Patient -' . $pid, $_SESSION['authUserID']);
  } else {
    log_user_event('eMAR- Header', 'Failed To Insert Data Into form_vitals For Patient -' . $pid, $_SESSION['authUserID']);
  }
  unset($_REQUEST['add_vitals_data']);
  header('Location: ' . $_SERVER['PHP_SELF']);
}
if (isset($_REQUEST['add_allergy'])) {
  $date = date("Y-m-d H:i:s");
  $pid = $_REQUEST['pid'];
  $allergy_type = $_REQUEST['allergy_type'];
  $allergen = $_REQUEST['allergen'];
  $reaction_type = $_REQUEST['reaction_type'];
  $reaction = $_REQUEST['reaction'];
  $begin_date = $_REQUEST['begin_date'];
  $treatment = $_REQUEST['treatment'];
  $status_code = $_REQUEST['status_code'];
  $source_of_report = $_REQUEST['source_of_report'];

  $qry = "INSERT INTO `form_allergies`(`id`, `date`, `pid`, `user`, `groupname`, `authorized`, `activity`, `allergy_type`, `allergen`, `reaction_type`, `reaction`, `begin_date`, `treatment`,`status_code`,`source_of_report`) VALUES (NULL,'$date','$pid',NULL,NULL,'0','1','$allergy_type','$allergen','$reaction_type','$reaction','$begin_date','$treatment','$status_code','$source_of_report')";
  //$res = sqlStatement($qry);

  if ($res = sqlStatement($qry)) {
    log_user_event('eMAR- Header', 'Inserted Data Into form_allergies For Patient -' . $pid, $_SESSION['authUserID']);
  } else {
    log_user_event('eMAR- Header', 'Failed To Insert Data Into form_allergies For Patient -' . $pid, $_SESSION['authUserID']);
  }

  header("Refresh: 0;");
}

?>


<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

  <script>
    var base_url = "<?= $GLOBALS['rootdir'] ?>";
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>


  <link rel="stylesheet" href="/public/assets/jquery-datetimepicker/build/jquery.datetimepicker.min.css?v=49" type="text/css">

  <script type="text/javascript" src="/public/assets/jquery-datetimepicker/build/jquery.datetimepicker.full.min.js?v=49"></script>

  <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap4.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= $webroot ?>/public/themes/dashboard.css">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script type="text/javascript" src="<?= $webroot ?>/library/dialog.js?v=<?php echo $v_js_includes; ?>"></script>

  <style>
    .cls_prn {
      background-color: #FED8B1;
    }

    .accordion {
      background-color: #eee;
      color: #444;
      cursor: pointer;
      padding: 18px;
      width: 100%;
      border: none;
      text-align: left;
      outline: none;
      font-size: 15px;
      transition: 0.4s;
    }

    .active,
    .accordion:hover {
      background-color: #ccc;
    }

    .panel {
      padding: 0 18px;
      display: none;
      background-color: white;
      overflow: hidden;
    }

    .accordion:after {
      content: '\02795';
      /* Unicode character for "plus" sign (+) */
      font-size: 13px;
      color: #777;
      float: right;
      margin-left: 5px;
    }

    .active:after {
      content: "\2796";
      /* Unicode character for "minus" sign (-) */
    }

    .btn-primary {
      text-decoration: none !important;
      background: #f2f5f6 !important;
      color: #06576a !important;
    }

    .form-control-medtime {
      width: 4rem;
      padding: .37rem .65rem;
    }
  </style>

  <style>
    ::-webkit-scrollbar {
      -webkit-appearance: none;
      width: 7px;
    }

    ::-webkit-scrollbar-thumb {
      border-radius: 4px;
      background-color: rgba(0, 0, 0, .5);
      -webkit-box-shadow: 0 0 1px rgba(255, 255, 255, .5);
    }

    body {
      margin: 0;
      font-family: "Montserrat", sans-serif !important;
      font-size: 11px;
    }

    .date_added_class {
      color: slategray
    }

    .drug_header {
      min-width: 350px;
    }

    .action_header {
      min-width: 250px;
    }

    .date_header {
      min-width: 55px;
    }

    .medtime_header {
      min-width: 75px;
    }

    .prov-note {
      color: blue;
      /*font-style: italic;*/
    }

    .provider_name {
      color: purple;
    }

    .modal {
      z-index: 1050 !important;
    }

    .modal-xl {
      max-width: 1140px !important;
    }

    .hideme {
      display: none;
    }

    .sidebar {
      margin: 0;
      padding: 0;
      width: 200px;
      background-color: #f1f1f1;
      position: fixed;
      height: 100%;
      overflow: auto;
      font-size: 14px;
      z-index: 1;
    }

    .sidebar a {
      display: block;
      color: black;
      padding: 16px;
      text-decoration: none;
    }

    .sidebar a.active {
      background-color: #0d819c;
      color: white;
    }

    .sidebar a:hover:not(.active) {
      background-color: rgba(47, 175, 205, 0.3);
      color: white;
    }

    div.content {
      margin-left: 200px;
      padding: 1px 16px;
      height: 1000px;
    }

    @media screen and (max-width: 700px) {
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
      }

      .sidebar a {
        float: left;
      }

      div.content {
        margin-left: 0;
      }
    }

    @media screen and (max-width: 400px) {
      .sidebar a {
        text-align: center;
        float: none;
      }
    }

    /* Old ones */
    .modal {
      display: none;
      /* Hidden by default */
      position: fixed;
      /* Stay in place */
      z-index: 1;
      /* Sit on top */
      padding-top: 100px;
      /* Location of the box */
      left: 0;
      top: 0;
      width: 100%;
      /* Full width */
      height: 100%;
      /* Full height */
      overflow: auto;
      /* Enable scroll if needed */
      background-color: rgb(0, 0, 0);
      /* Fallback color */
      background-color: rgba(0, 0, 0, 0.4);
      /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
      position: relative;
      background-color: #fefefe;
      margin: auto;
      padding: 0;
      border: 1px solid #888;
      width: 80%;
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
      -webkit-animation-name: animatetop;
      -webkit-animation-duration: 0.4s;
      animation-name: animatetop;
      animation-duration: 0.4s
    }

    /* Add Animation */
    @-webkit-keyframes animatetop {
      from {
        top: -300px;
        opacity: 0
      }

      to {
        top: 0;
        opacity: 1
      }
    }

    @keyframes animatetop {
      from {
        top: -300px;
        opacity: 0
      }

      to {
        top: 0;
        opacity: 1
      }
    }

    /* The Close Button */
    .close {
      color: white;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: #000;
      text-decoration: none;
      cursor: pointer;
    }

    /* .modal-header {
      padding: 2px 16px;
      background-color: #5cb85c;
      color: white;
    }

    .modal-body {
      padding: 2px 16px;
    }

    .modal-footer {
      padding: 2px 16px;
      background-color: #5cb85c;
      color: white;
    } */

    .p-tag {
      font-size: 14px;
    }

    .accordion {
      background-color: #eee;
      color: #444;
      cursor: pointer;
      padding: 18px;
      width: 100%;
      border: none;
      text-align: left;
      outline: none;
      font-size: 15px;
      transition: 0.4s;
    }

    .active,
    .accordion:hover {
      background-color: #ccc;
    }

    .panel {
      padding: 0 18px;
      display: none;
      background-color: white;
      overflow: hidden;
    }


    .btn-default:hover {
      background: #06576a !important;
      color: #fff !important;
      border: 1px solid #06576a;
      font-size: 12px;
      font-weight: 500;
    }

    .btn-default {
      text-decoration: none !important;
      background: #f2f5f6 !important;
      color: #06576a !important;
      border: 1px solid #06576a;
      font-size: 12px;
      font-weight: 500;
      font-family: "Montserrat", sans-serif !important;
      border-radius: 1px !important;
      padding: 10px;
    }

    .dataTables_length select {
      padding: 0.375rem 1.75rem 0.375rem 0.75rem;
    }

    table.dataTable thead th {
      background: #06576a;
      color: #fff;
    }

    .modal-backdrop.show {
      opacity: 0;
    }
  </style>

  <!-- By Rudrayya For Med Time Dropdown 20-09-2022 Start -->
  <style>
    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
      padding: 12px 16px;
      z-index: 1;
      overflow-y: scroll;
      max-height: 200px;
      min-height: 10px;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    /* Color Codes 22-09-2022 */
    .inactive_med {
      filter: blur(1px);
      background-color: #D5DBDB;
    }

    .PRN_med {
      background-color: #FDEBD0;
    }

    .stat_does_med {
      background-color: #AED6F1;
    }

    .from_orderset_med {
      background-color: #D1F2EB;
    }

    .orderset_border {
      border-left: solid;
    }

    .h25 {
      height: 25px;
    }

    .drug_font {
      color: #000;
      font-weight: bold;
      font-size: 16px;
    }

    td {
      border-bottom: 1px solid black;
    }

    .medtime_header .dropdown .dropdown-content p,
    .medtime_header .dropdown span u,
    td.date_start,
    td.date_end {
      font-size: 15px;
      font-weight: bold;
    }
  </style>
  <!-- By Rudrayya For Med Time Dropdown 20-09-2022 End -->

  <title>e-MAR</title>
  <!-- <script type="text/javascript" src="dist/jquery.tabledit.js"></script> -->
  <?php
  // include('container.php');
  ?>




</head>