<?php
require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");

if ($_POST['disable_meds'] == "yEs") {
	$ids = explode(',', $_POST['ids']);
	$cancelled_reason = $_POST['discontinuation_reason'];
	$cancelled_provider = $_POST['provider_id'];
	$cancelled_verbal = $_POST['verbal'];
	$date = date("Y-m-d H:i:s");
	$date2 = date("Y-m-d");

	foreach ($ids as $value) {

		$query1 = "UPDATE prescriptions SET `updated_by`='$cancelled_by',`date_modified`='$date',`active`='0',`cancelled_reason` = '$cancelled_reason' ,`cancelled_provider` = '$cancelled_provider' ,`cancelled_verbal` = '$cancelled_verbal' WHERE id ='$value' ";
		if ($res1 = sqlStatement($query1)) {
			log_user_event('eMAR - Updated Prescription', 'Prescription Details has been Updated to inactive Id :' . $value, $_SESSION['authUserID']);
		} else {
			log_user_event('eMAR - Updated Prescription', 'Failed to Updated Prescription Deatisl has been Updated to inactive Id :' . $value, $_SESSION['authUserID']);
		}

		$query2 = "UPDATE `med_logs` SET `active`='0',`date_modified`='$date2',`updated_by`='$cancelled_by' WHERE `prescription_id` ='" . $value . "' ";
		if ($res2 = sqlStatement($query2)) {
			log_user_event('eMAR - Updated medlog', 'Medlog has been Updated to inactive Id :' . $value, $_SESSION['authUserID']);
		} else {
			log_user_event('eMAR - Updated Prescription', 'Failed to Updated Medlog has been Updated to inactive Id :' . $value, $_SESSION['authUserID']);
		}
	}
}

if ($_POST['discontinue_doc_orders'] == "yEs") {
	$ids = explode(',', $_POST['ids']);
	$doc_order_discontinuation_reason = $_POST['doc_order_discontinuation_reason'];
	$doc_order_provider_id = $_POST['doc_order_provider_id'];
	$doc_order_verbal = $_POST['doc_order_verbal'];

	foreach ($ids as $value) {
		$query1 = "UPDATE doctors_order SET `discontinued`='1',discontinuation_reason='$doc_order_discontinuation_reason',provider_id='$doc_order_provider_id',verbal_order='$doc_order_verbal' WHERE id ='$value' ";
		if ($res1 = sqlStatement($query1)) {
			log_user_event('eMAR - Updated Doctors Orders', 'Doctors Orders Details has been Updated to inactive Id :' . $value, $_SESSION['authUserID']);
		} else {
			log_user_event('eMAR - Updated Doctors Orders', 'Failed to Updated Doctors Orders Deatisl has been Updated to inactive Id :' . $value, $_SESSION['authUserID']);
		}
	}
}
