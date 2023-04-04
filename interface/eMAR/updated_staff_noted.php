<?php
require_once(dirname(__FILE__) . "/../globals.php");

use OpenEMR\Common\Csrf\CsrfUtils;

if (!CsrfUtils::verifyCsrfToken($_GET["csrf_token_form"])) {
    CsrfUtils::csrfNotVerified();
}

if(isset($_POST['med_id']))
{
    $result = '';
    $med_id = $_POST['med_id'];
    $pid = $GLOBALS['pid'];
    $query = "update med_logs set administered_note ='". $_POST['staff_note'] ."'  where id =". $_POST['med_id'];
	echo $query;
    //$res3 = sqlStatement($query);
	if($res3 = sqlStatement($query)){
		log_user_event('eMAR - Updated Staff noted', 'med_logs Details Updated Id :'.$_POST['med_id'], $_SESSION['authUserID']);
	}else{
		log_user_event('eMAR - Updated Staff noted', 'Failed to Updated med_logs Deatisl Id :'.$_POST['med_id'], $_SESSION['authUserID']);
	}
	
}

echo $_POST['staff_note'];

