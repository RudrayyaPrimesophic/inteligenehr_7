<?php
require_once("../../globals.php");
require_once("$srcdir/options.inc.php");

use OpenEMR\Common\Csrf\CsrfUtils;

echo 'csrf_token_form:' . $_GET["csrf_token_form"];
if (!CsrfUtils::verifyCsrfToken($_GET["csrf_token_form"])) {
  CsrfUtils::csrfNotVerified();
}

$logged_usser = $_SESSION["authUser"];

if (isset($_POST['add_vitals_data'])) {
  $last_id = generate_id();
  $date= date('Y-m-d H:i:s');
  $date2 = date('m/d/Y');
  $pid = $GLOBALS['pid'];
  $date_time = ($_POST['date']) ? ($_POST['date']) : date('Y-m-d H:i:s');
  $date_time = date('Y-m-d H:i:s', strtotime($date_time));
  $weight = $_POST['weight'];
  $height = $_POST['height'];
  $bps = $_POST['bps'];
  $bpd = $_POST['bpd'];
  $pulse = $_POST['pulse'];
  $temperature = $_POST['temperature'];
  $respiration = $_POST['respiration'];
  $saturation = $_POST['saturation'];
  $bmi = $_POST['bmi'];
  $encounter = $_SESSION['encounter'];

  $form_name = 'Vitals -' . $date2;
  $user = $_SESSION['authUser'];
  $userid = $_SESSION['authUserID'];

  $qry = "INSERT INTO `form_vitals`(`id`, `date`, `pid`, `user`, `groupname`, `authorized`, `activity`,`date_time`, `weight`, `height`, `bps`, `bpd`, `pulse`, `temperature`,`respiration`,`oxygen_saturation`,`BMI`) 
  VALUES ($last_id,'$date','$pid',NULL,NULL,'0','1','$date_time','$weight','$height','$bps','$bpd','$pulse','$temperature','$respiration','$saturation','$bmi')";
  $vitals_id = sqlInsert($qry);
  if ($vitals_id > 0) {
    $qry2 = "INSERT INTO `forms` (`id`, `date`, `encounter`, `form_name`, `form_id`, `pid`, `user`,
    `groupname`, `authorized`, `deleted`, `formdir`, `therapy_group_id`,
     `issue_id`, `provider_id`, `is_locked`, `updated`, `updated_by`, `is_revoked`, `revoked_reason`)
      VALUES (NULL,'$date','$encounter','$form_name','$vitals_id','$pid','$user','Default','1','0','vitals',NULL,'0','0','1','$date','$userid','0',NULL)";
    $form_id = sqlInsert($qry2);
    log_user_event('eMAR- Header', 'Inserted Data Into form_vitals For Patient -' . $pid, $_SESSION['authUserID']);
  } else {
    log_user_event('eMAR- Header', 'Failed To Insert Data Into form_vitals For Patient -' . $pid, $_SESSION['authUserID']);
  }
  
}
?>