<?php

/**
 * dynamic_finder_ajax.php
 *
 * Sponsored by David Eschelbacher, MD
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Rod Roark <rod@sunsetsystems.com>
 * @author    Brady Miller <brady.g.miller@gmail.com>
 * @author    Jerry Padgett <sjpadgett@gmail.com>
 * @copyright Copyright (c) 2012 Rod Roark <rod@sunsetsystems.com>
 * @copyright Copyright (c) 2018 Brady Miller <brady.g.miller@gmail.com>
 * @copyright Copyright (c) 2019 Jerry Padgett <sjpadgett@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */


require_once(dirname(__FILE__) . "/../globals.php");
require_once($GLOBALS['fileroot'] . "/library/options.inc.php");
require_once($GLOBALS['fileroot'] . "/library/patient.inc");
require_once($GLOBALS['fileroot'] . "/library/acl.inc");

use OpenEMR\Common\Csrf\CsrfUtils;

if (!CsrfUtils::verifyCsrfToken($_GET["csrf_token_form"])) {
    CsrfUtils::csrfNotVerified();
}

if(isset($_POST['med_id']))
{
    $encounter = $_SESSION['encounter'];
    $result = '';
    $med_id = $_POST['med_id'];
    $pid = $GLOBALS['pid'];
	$updated_by = $_SESSION['authUserID'];
	$start_date = date('Y-m-d 00:00:00');
    $query = "update form_med_reconcilation_brought_in set broughtin_status = 1 where pid = '". $pid ."' and id='". $med_id ."' ";
    $res3 = sqlStatement($query);
    //if($med_brought_insert_id > 0 && $continue_discharge != 1 && $hold != 1)
    if($med_id != '') {
        $qry2 = "INSERT INTO `prescriptions`( `date_added`,`start_date`, `patient_id`, `drug`, `dosage`,`interval`,`quantity`,`provider_id`,`size`,`unit`,`form`,`route`,`note`,`med_time`,`updated_by`,`drug_id`,`verbal`,`encounter`,`med_brought_in_id`, `continue_on_discharge`)
        SELECT * FROM (SELECT date,'".$start_date."',pid,medication,dosage,frequency,amnt_on_hand,approved_by,size,unit,form,route,warning_txt,med_time,'".$updated_by."',drug_id,verbal,'".$encounter."',id, continue_discharge 
        FROM form_med_reconcilation_brought_in  where pid ='". $pid ."' and  id='". $med_id ."' ) as tmp
        WHERE NOT EXISTS (
        SELECT * FROM prescriptions WHERE patient_id = '". $pid ."' and med_brought_in_id='". $med_id ."') ";
        echo $qry2;
        if(sqlStatement($qry2)){
			log_user_event('eMAR - Meds Brought-In Prescribe', 'Patient Prescription in Added :'.$pid, $_SESSION['authUserID']);
		}else{
			log_user_event('eMAR - Meds Brought-In Prescribe', 'Patient Prescription Failed to add :'.$pid, $_SESSION['authUserID']);
		}			

        
			
        $max_qry = "SELECT MAX(id) as max_id FROM prescriptions";
        $max_res = sqlStatement($max_qry);
        $max_row = sqlFetchArray($max_res);
        $max_id = $max_row['max_id'];
        addMeds($max_id);
        $result = 1;
       
    }else{
        $result = 0; 
    }
    echo $result;
	
}

