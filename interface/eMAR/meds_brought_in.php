<?php
require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");

use OpenEMR\Common\Csrf\CsrfUtils;
use OpenEMR\Core\Header;

if (isset($_POST['add_meds_broughtin']) && !empty($_POST['add_meds_broughtin'])) {
	$date = date("Y-m-d H:i:s");
	$pid = $_POST['pid'];
	$medication = $_POST['medication'];
	$dosage = $_POST['dosage'];
	$hold = $_POST['hold'];
	$frequency = $_POST['frequency'];
	if (isset($_POST['is_prn'])) {
		$is_prn = 1;
	} else {
		$is_prn = 0;
	}
	$amnt_on_hand = $_POST['amnt_on_hand'];
	$last_taken = '';
	if (isset($_POST['last_taken']) && !empty($_POST['last_taken'])) {
		$last_taken = $_POST['last_taken'];
	}
	$prescribe = $_POST['prescribe'];
	$logged_by = $_POST['logged_by'];
	$approved_by = $_POST['approved_by'];
	$verbal = $_POST['verbal'];
	$amt_returned = $_POST['amt_returned'];
	$continue_discharge = $_POST['continue_discharge'];
	$amount_destroyed = $_POST['amount_destroyed'];
	$time = '';
	if (isset($_POST['time']) && !empty($_POST['time'])) {
		$time = $_POST['time'];
	}
	$witness = $_POST['witness'];
	$size = $_POST['size'];
	$unit = $_POST['unit'];
	$route = $_POST['route'];
	$form = $_POST['form'];
	$warning_txt = $_POST['warning_txt'];
	$med_time =  implode(',', array_filter($_POST['med_time']));
	$drug_id = $_POST['drug_id'];
	$encounter = $_POST['encounter'];


	$qry = "INSERT INTO `form_med_reconcilation_brought_in`(`id`, `date`, `pid`, `user`, `groupname`, `authorized`, `activity`, `medication`, `dosage`,`hold`, `frequency`,`is_prn`,`amnt_on_hand`, `last_taken`, `prescribe`,`logged_by`,`approved_by`,`verbal`,`amt_returned`,`continue_discharge`,`amount_destroyed`,`time`,`witness`,`size`,`unit`,`route`,`form`,`warning_txt`,`med_time`,`drug_id`,`encounter`) VALUES (NULL,'$date','$pid',NULL,NULL,'0','1','$medication','$dosage','$hold','$frequency','$is_prn','$amnt_on_hand','$last_taken','$prescribe','$logged_by','$approved_by','$verbal','$amt_returned','$continue_discharge','$amount_destroyed','$time','$witness','$size','$unit','$route','$form','$warning_txt','$med_time','$drug_id','$encounter')";

	$med_brought_insert_id = sqlInsert($qry);

	if ($med_brought_insert_id > 0) {
		log_user_event('eMAR - Meds Brought-In', 'Patient Meds Brought in Added :' . $pid, $_SESSION['authUserID']);
		echo 1;
	} else {
		log_user_event('eMAR - Meds Brought-In', 'Patient Meds Brought Failed to add :' . $pid, $_SESSION['authUserID']);
		echo 0;
	}

	// if($med_brought_insert_id > 0 && $continue_discharge != 1 && $hold != 1){
	// 	$qry2 = "INSERT INTO `prescriptions`(`id`, `date_added`,`start_date`, `patient_id`, `user`, `drug`, `dosage`,`interval`,`quantity`,`med_brought_in_id`) VALUES (NULL,'$date','$date','$pid',NULL,'$medication','$dosage','$frequency','$amnt_on_hand','$med_brought_insert_id')";
	// 	$res2 = sqlStatement($qry2);

	//     $max_qry = "SELECT MAX(id) as max_id FROM prescriptions";

	//     $max_res = sqlStatement($max_qry);
	//     $max_row = sqlFetchArray($max_res);
	//     $max_id = $max_row['max_id'];

	//     addMeds($max_id);


	// }

}
