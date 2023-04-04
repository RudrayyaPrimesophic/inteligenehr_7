<?php
require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");

use OpenEMR\Common\Csrf\CsrfUtils;
use OpenEMR\Core\Header;

//   $sql_query_current = "select * from orderset";
//   $res_current = sqlStatement($sql_query_current);
if($_POST['orderset_id'] && $_POST['check_box'] == 0){
	$query = 'UPDATE orderset SET name="'. $_POST['txtval'] . '" WHERE id ='. $_POST['orderset_id'];
	if(sqlStatement($query)){
		log_user_event('eMAR - Updated Orderset', 'Orderset Details Updated Id :'.$_POST['orderset_id'], $_SESSION['authUserID']);
	}else{
		log_user_event('eMAR - Updated Orderset', 'Failed to Updated Orderset Deatisl Id :'.$_POST['orderset_id'], $_SESSION['authUserID']);
	}
}else if($_POST['orderset_id'] && $_POST['check_box'] == 1){
	$query = 'UPDATE orderset SET is_special="'. $_POST['txtval'] . '" WHERE id ='. $_POST['orderset_id'];
	if(sqlStatement($query)){
		log_user_event('eMAR - Updated Orderset', 'Orderset Details Updated Id :'.$_POST['orderset_id'], $_SESSION['authUserID']);
	}else{
		log_user_event('eMAR - Updated Orderset', 'Failed to Updated Orderset Deatisl Id :'.$_POST['orderset_id'], $_SESSION['authUserID']);
	}
}