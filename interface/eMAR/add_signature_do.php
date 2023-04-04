<?php
require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");
// require_once("../../../../sites/default/sqlconf.php");

$pid = $_POST['pid'];
// $staffsign = $_POST['staffsign'];
$user_id = $_POST['user_id'];

$do_ids = $_POST['do_ids'];
$do_id = explode(',', $do_ids);

foreach ($do_id as $key => $value) {

    $sql_p = "SELECT `discontinued` From doctors_order WHERE `id`='$value'";
    $res_p = sqlStatement($sql_p);
    $row_p = sqlFetchArray($res_p);

    date_default_timezone_set("America/New_York");
    $date = date("Y-m-d H:i:s");
    $sql_qry = "INSERT INTO `doctors_order_signature`(doctors_orders_id,active,signer_id,signed_at) values ( '" . $value . "','" . $row_p['discontinued'] . "','" . $user_id . "','" . $date . "')";
    if (sqlStatement($sql_qry)) {
        log_user_event('eMAR-Verbal Orders', 'doctor Order Signed For Patient -' . $pid, $_SESSION['authUserID']);
        $data = ['status' => true];
    } else {
        log_user_event('eMAR-Verbal Orders', 'Failed To Sign doctor Order For Patient -' . $pid, $_SESSION['authUserID']);
        $data = ['status' => false];
    }
}
echo json_encode($data);
