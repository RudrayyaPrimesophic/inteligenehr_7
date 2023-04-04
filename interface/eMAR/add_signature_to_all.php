<?php
// include_once("db_connect.php");
require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");
// require_once("../../../../sites/default/sqlconf.php");

$pid = $_POST['pid'];
// $staffsign = $_POST['staffsign'];
$user_id = $_POST['user_id'];
date_default_timezone_set("America/New_York");
$date = date("Y-m-d H:i:s.u");

$prescriptions_id = $_POST['prescriptions_id'];
$prescriptions = explode(',', $prescriptions_id);


$do_ids = $_POST['do_ids'];
$do_id = explode(',', $do_ids);

if (isset($_SERVER['HTTP_CLIENT_IP'])) {
    $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
} else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
} else if (isset($_SERVER['HTTP_FORWARDED'])) {
    $ipaddress = $_SERVER['HTTP_FORWARDED'];
} else if (isset($_SERVER['REMOTE_ADDR'])) {
    $ipaddress = $_SERVER['REMOTE_ADDR'];
} else {
    $ipaddress = 'UNKNOWN';
}

$sql_qry = "INSERT INTO `medlogs_signature_header`(pid,staff_id,inserted_at,ip_address) values ( '" . $pid . "','" . $user_id . "','" . $date . "','" . $ipaddress . "')";
if (sqlStatement($sql_qry)) {
    $query_msh = "SELECT `header_id` FROM `medlogs_signature_header` WHERE `pid`='$pid' AND `staff_id`='$user_id' AND `inserted_at`='$date' ORDER BY `header_id` DESC";
    $res_msh = sqlStatement($query_msh);
    $msh = sqlFetchArray($res_msh);

    foreach ($prescriptions as $key => $value) {

        $sql_p = "SELECT `active` From prescriptions WHERE `id`='$value'";
        $res_p = sqlStatement($sql_p);
        $row_p = sqlFetchArray($res_p);

        $sql = "INSERT INTO `medlogs_signature`(header_id,prescription_id,active,signed_at)values('" . $msh['header_id'] . "','" . $value . "','" . $row_p['active'] . "','" . $date . "')";
        if (sqlStatement($sql)) {
            log_user_event('eMAR-add_signature_to_all', 'Verbal Order Signed For Patient -' . $pid, $_SESSION['authUserID']);
            $data = ['status' => true, 'id' => $msh['header_id']];
        } else {
            log_user_event('eMAR-add_signature_to_all', 'Failed To Sign Verbal Order For Patient -' . $pid, $_SESSION['authUserID']);
            $data = ['status' => false];
        }
    }

    foreach ($do_id as $key => $value) {
        $sql_qry = "INSERT INTO `doctors_order_signature`(doctors_orders_id,signer_id,signed_at) values ( '" . $value . "','" . $user_id . "','" . $date . "')";
        if (sqlStatement($sql_qry)) {
            log_user_event('eMAR-add_signature_to_all', 'doctor Order Signed For Patient -' . $pid, $_SESSION['authUserID']);
            $data = ['status' => true, 'id' => $msh['header_id']];
        } else {
            log_user_event('eMAR-add_signature_to_all', 'Failed To Sign doctor Order For Patient -' . $pid, $_SESSION['authUserID']);
            $data = ['status' => false];
        }
    }
} else {
    $data = ['status' => false];
}
echo json_encode($data);
