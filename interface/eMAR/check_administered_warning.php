<?php
require_once(dirname(__FILE__) . "/../globals.php");
  require_once("$srcdir/options.inc.php");

use OpenEMR\Common\Csrf\CsrfUtils;

if (!CsrfUtils::verifyCsrfToken($_GET["csrf_token_form"])) {
   CsrfUtils::csrfNotVerified();
}

$prescription_id = $_GET['prescription_id'];

$sql = 'SELECT med_time from prescriptions where id='. $prescription_id;
$res = sqlStatement($sql);
$row = sqlFetchArray($res);
$med_time = $row['med_time'];
$count = count(explode(',', $med_time));

$sql = "SELECT LEFT(update_time , 10) = date_format(curdate(), '%m-%d-%Y') as todays, update_time  from med_logs where  did_administer = 1 AND prescription_id=". $prescription_id. " HAVING todays > 0 ORDER BY id ASC";
$res = sqlStatement($sql);
$todays_count = 0;
$last_val = '';
while($row = sqlFetchArray($res)) { $todays_count++;$last_val = $row; };

$data = array($count, $todays_count, $med_time, substr($last_val['update_time'], 11, 5));
echo json_encode($data);