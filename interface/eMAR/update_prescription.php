<?php
require_once(dirname(__FILE__) . "/../globals.php");
require_once($srcdir . "/options.inc.php");

use OpenEMR\Common\Csrf\CsrfUtils;

if (!CsrfUtils::verifyCsrfToken($_GET["csrf_token_form"])) {
	CsrfUtils::csrfNotVerified();
}


if (isset($_POST['med_id'])) {
	$result = '';
	$med_id = $_POST['med_id'];
	$pid = $GLOBALS['pid'];
	$query = "update med_logs set continue_on_discharge ='" . $_POST['continue_on_discharge'] . "'  where prescription_id =" . $_POST['med_id'];
	//$res3 = sqlStatement($query);
	if ($res3 = sqlStatement($query)) {
		log_user_event('eMAR - Updated Prescription', 'med_logs Details Updated Id :' . $_POST['med_id'], $_SESSION['authUserID']);
	} else {
		log_user_event('eMAR - Updated Prescription', 'Failed to Updated med_logs Deatisl Id :' . $_POST['med_id'], $_SESSION['authUserID']);
	}
	echo $query;
	$query = "update prescriptions set continue_on_discharge ='" . $_POST['continue_on_discharge'] . "'  where id =" . $_POST['med_id'];
	//$res3 = sqlStatement($query);
	if ($res3 = sqlStatement($query)) {
		log_user_event('eMAR - Updated Prescription', 'prescriptions Details Updated Id :' . $_POST['med_id'], $_SESSION['authUserID']);
	} else {
		log_user_event('eMAR - Updated Prescription', 'Failed to Updated prescriptions Deatisl Id :' . $_POST['med_id'], $_SESSION['authUserID']);
	}
	echo $query;
}
