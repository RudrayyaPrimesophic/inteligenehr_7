<?php
include("popup_head.php");


/**
* @ $_GET['action'] 1: Administrate, 2: Refused, 3: Late, 4 Held
*/

$action = (isset($_GET['action']) && !empty($_GET['action'])) ? $_GET['action'] : 1;

$action_btn = 'Mark as ';
$action_btn_class = 'btn-default';
                    if($action == '1') { $action_btn .= 'Administered'; $action_btn_class = 'btn-primary'; }
                    else if($action == '2') { $action_btn .= 'Refused'; $action_btn_class = 'btn-danger'; }
                    else if($action == '3') { $action_btn .= 'Late'; $action_btn_class = 'btn-warning'; }
                    else if($action == '4') {$action_btn .= 'Held'; $action_btn_class = 'btn-secondary'; }
                    else $action_btn .= 'Done';

                    if (isset($_REQUEST['id']) && $_POST['singleadmin'] == 'true') {

                        $id = $_REQUEST['id'];
                        $note = $_REQUEST['staff_note'];
                        $user_name = $_SESSION['authUser'];
                        if (isset($_REQUEST['glucose_reading'])) {
                            $glucose_reading = $_REQUEST['glucose_reading'];
                        } else {
                            $glucose_reading = NULL;
                        }
                    
                        if ($_REQUEST['interval_id'] == 17 || $_REQUEST['is_prn'] == 1) {
                            $query = "INSERT INTO med_logs (`prescriptionguid`,`prescription_id`,`patient_id`,`filled_by_id`,`pharmacy_id`,`date_added`,`date_modified`,`provider_id`,`encounter`,`start_date`,`drug`,`warning_txt`,`verbal`,`drug_id`,`rxnorm_drugcode`,`form`,`dosage`,`quantity`,`size`,`unit`,`route`,`interval`,`instruction`,`substitute`,`refills`,`per_refill`,`filled_date`,`medication`,`note`,`active`,`med_time`,`administered_by`,`site`,`erx_source`,`erx_uploaded`,`drug_info_erx`,`external_id`,`end_date`,`indication`,`prn`,`ntx`,`rtx`,`txDate`,`appt_id`,`did_administer`,`pain_scale`,`glucose_reading`,`patient_intake`,`patient_signed`,`administered_note`,`datetime`,`patient_signed_time`, `is_prn`)
                            SELECT `prescriptionguid`,`prescription_id`,`patient_id`,`filled_by_id`,`pharmacy_id`,`date_added`,`date_modified`,`provider_id`,`encounter`,`start_date`,`drug`,`warning_txt`,`verbal`,`drug_id`,`rxnorm_drugcode`,`form`,`dosage`,`quantity`,`size`,`unit`,`route`,`interval`,`instruction`,`substitute`,`refills`,`per_refill`,`filled_date`,`medication`,`note`,`active`,`med_time`,`administered_by`,`site`,`erx_source`,`erx_uploaded`,`drug_info_erx`,`external_id`,`end_date`,`indication`,`prn`,`ntx`,`rtx`,`txDate`,`appt_id`,`did_administer`,`pain_scale`,`glucose_reading`,`patient_intake`,`patient_signed`,`administered_note`,`datetime`,`patient_signed_time`, `is_prn` FROM `med_logs` WHERE id ='" . $id . "'";
                    
                            //sqlStatement($query);
							if(sqlStatement($query)){
								log_user_event('eMAR - Administrative Popup', 'Inserted into med_logs For Id:'.$_REQUEST['id'], $_SESSION['authUserID']);
							}else{
								log_user_event('eMAR - Administrative Popup', 'Failed to Insert into med_logs For Id:'.$_REQUEST['id'], $_SESSION['authUserID']);
							}
							
                            $res = sqlStatement('SELECT MAX(id) as id FROM med_logs');
                            $row = sqlFetchArray($res);
                            $id = $row['id'];
                            header("Refresh: 1;");
                        }
                    
                        $update_date = date('m-d-Y H:i:s');
                        if (isset($_REQUEST['pain_sacle'])) {
                            $pain_scale = $_REQUEST['pain_sacle'];
                    
                            if ($pain_scale > 0 && $pain_scale < 4) {
                                $pain_scalef = 'mild';
                            } elseif ($pain_scale >= 4 && $pain_scale < 6) {
                                $pain_scalef = 'moderate ';
                            } elseif ($pain_scale >= 6 && $pain_scale < 9) {
                                $pain_scalef = 'high';
                            } elseif ($pain_scale >= 9 && $pain_scale <= 10) {
                                $pain_scalef = 'severe';
                            } else {
                                $pain_scalef = 'No Pain';
                            }
                            $upd_qry = "UPDATE `med_logs` SET ";
                    
                            if (isset($_REQUEST['refused']) && $_REQUEST['refused'] == '1') {
                                $upd_qry .= " `did_refused`= 1, `administered_note` = '$note', `status` = 'Refused',`update_time`='$update_date',";
                            } else if (isset($_REQUEST['late']) && $_REQUEST['late'] == '1') {
                                $upd_qry .= " `did_administer`= 1, `administered_note` = '$note',`status` = 'Administered Late',`update_time`='$update_date',";
                            } else if (isset($_REQUEST['held']) && $_REQUEST['held'] == '1') {
                                $upd_qry .= "  `administered_note` = '$note',`status` = 'Held',`update_time`= '$update_date',";
                            }else if (isset($_REQUEST['missed']) && $_REQUEST['missed'] == '1') {
                                $upd_qry .= "  `administered_note` = '$note',`status` = 'Missed',`update_time`= '$update_date',";
                            } else {
                                $upd_qry .= " `did_administer`= 1,`administered_note`='$note', `status` = 'Administered',`update_time`='$update_date',";
                            }
                            $upd_qry .= " `administered_by`='$user_name',`pain_scale`='$pain_scalef',`glucose_reading`='$glucose_reading' WHERE `id`=$id";
                        } else {
                            $upd_qry = "UPDATE `med_logs` SET ";
                            if (isset($_REQUEST['refused']) && $_REQUEST['refused'] == '1') {
                                $upd_qry .= " `did_refused`= 1, `administered_note` = '$note', `status` = 'Refused',`update_time`='$update_date',";
                            } else if (isset($_REQUEST['late']) && $_REQUEST['late'] == '1') {
                                $upd_qry .= " `did_administer`= 1, `administered_note` = '$note',`status` = 'Administered Late',`update_time`='$update_date',";
                            } else if (isset($_REQUEST['held']) && $_REQUEST['held'] == '1') {
                                $upd_qry .= "  `administered_note` = '$note',`status` = 'Held',`update_time`= '$update_date',";
                            } else if (isset($_REQUEST['missed']) && $_REQUEST['missed'] == '1') {
                                $upd_qry .= "  `administered_note` = '$note',`status` = 'Missed',`update_time`= '$update_date',";
                            } else {
                                $upd_qry .= " `did_administer`= 1,`administered_note`='$note', `status` = 'Administered',`update_time`='$update_date',";
                            }
                            $upd_qry .= " `administered_by`='$user_name',`glucose_reading`='$glucose_reading' WHERE `id`=$id";
                        }
                        //echo $upd_qry;
    if($upd_res = sqlStatement($upd_qry)){
		log_user_event('eMAR - Administrative Popup', 'Updated med_logs For Id:'.$id, $_SESSION['authUserID']);
	}else{
		log_user_event('eMAR - Administrative Popup', 'Failed to Update med_logs For Id:'.$id, $_SESSION['authUserID']);
	}				
    exit;
}

/// pop up submissioin med administer
if(isset($_REQUEST['multimeds']) && !empty($_REQUEST['multimeds']) && $_POST['multiadmin'] =='multiadmin') {

    $action = (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) ? $_REQUEST['action'] : 1;
    $multimeds = explode(':',$_REQUEST['multimeds']);
    foreach($multimeds as $key => $id) {

        if(empty($id)) continue;
        $note = $_REQUEST['staff_note'][$id][0];
     
        $user_name = $_SESSION['authUser'];
        if (isset($_REQUEST['glucose_reading'][$id][0])) {
            $glucose_reading = $_REQUEST['glucose_reading'][$id][0];
        } else {
            $glucose_reading = NULL;
        }
        
        if($_REQUEST['interval_id'][$id][0] == 17 ){
            $query = "INSERT INTO med_logs (`prescriptionguid`,`prescription_id`,`patient_id`,`filled_by_id`,`pharmacy_id`,`date_added`,`date_modified`,`provider_id`,`encounter`,`start_date`,`drug`,`warning_txt`,`verbal`,`drug_id`,`rxnorm_drugcode`,`form`,`dosage`,`quantity`,`size`,`unit`,`route`,`interval`,`instruction`,`substitute`,`refills`,`per_refill`,`filled_date`,`medication`,`note`,`active`,`med_time`,`administered_by`,`site`,`erx_source`,`erx_uploaded`,`drug_info_erx`,`external_id`,`end_date`,`indication`,`prn`,`ntx`,`rtx`,`txDate`,`appt_id`,`did_administer`,`pain_scale`,`glucose_reading`,`patient_intake`,`patient_signed`,`administered_note`,`datetime`,`patient_signed_time`, `is_prn`)
            SELECT `prescriptionguid`,`prescription_id`,`patient_id`,`filled_by_id`,`pharmacy_id`,`date_added`,`date_modified`,`provider_id`,`encounter`,`start_date`,`drug`,`warning_txt`,`verbal`,`drug_id`,`rxnorm_drugcode`,`form`,`dosage`,`quantity`,`size`,`unit`,`route`,`interval`,`instruction`,`substitute`,`refills`,`per_refill`,`filled_date`,`medication`,`note`,`active`,`med_time`,`administered_by`,`site`,`erx_source`,`erx_uploaded`,`drug_info_erx`,`external_id`,`end_date`,`indication`,`prn`,`ntx`,`rtx`,`txDate`,`appt_id`,`did_administer`,`pain_scale`,`glucose_reading`,`patient_intake`,`patient_signed`,`administered_note`,`datetime`,`patient_signed_time`, `is_prn` FROM `med_logs` WHERE id ='" .$id . "'";
            if(sqlStatement($query)){
				log_user_event('eMAR - Administrative Popup', 'Inserted Into med_logs For Id:'.$id, $_SESSION['authUserID']);
			}else{
				log_user_event('eMAR - Administrative Popup', 'Failed to Insert into med_logs For Id:'.$id, $_SESSION['authUserID']);
			}
			
            $res = sqlStatement('SELECT MAX(id) as id FROM med_logs');
            $row = sqlFetchArray($res);
            $id = $row['id'];
        }

        
        if($_REQUEST['is_prn'][$id][0] == 1 ){
            $query = "INSERT INTO med_logs (`prescriptionguid`,`prescription_id`,`patient_id`,`filled_by_id`,`pharmacy_id`,`date_added`,`date_modified`,`provider_id`,`encounter`,`start_date`,`drug`,`warning_txt`,`verbal`,`drug_id`,`rxnorm_drugcode`,`form`,`dosage`,`quantity`,`size`,`unit`,`route`,`interval`,`instruction`,`substitute`,`refills`,`per_refill`,`filled_date`,`medication`,`note`,`active`,`med_time`,`administered_by`,`site`,`erx_source`,`erx_uploaded`,`drug_info_erx`,`external_id`,`end_date`,`indication`,`prn`,`ntx`,`rtx`,`txDate`,`appt_id`,`did_administer`,`pain_scale`,`glucose_reading`,`patient_intake`,`patient_signed`,`administered_note`,`datetime`,`patient_signed_time`, `is_prn`)
            SELECT `prescriptionguid`,`prescription_id`,`patient_id`,`filled_by_id`,`pharmacy_id`,`date_added`,`date_modified`,`provider_id`,`encounter`,`start_date`,`drug`,`warning_txt`,`verbal`,`drug_id`,`rxnorm_drugcode`,`form`,`dosage`,`quantity`,`size`,`unit`,`route`,`interval`,`instruction`,`substitute`,`refills`,`per_refill`,`filled_date`,`medication`,`note`,`active`,`med_time`,`administered_by`,`site`,`erx_source`,`erx_uploaded`,`drug_info_erx`,`external_id`,`end_date`,`indication`,`prn`,`ntx`,`rtx`,`txDate`,`appt_id`,`did_administer`,`pain_scale`,`glucose_reading`,`patient_intake`,`patient_signed`,`administered_note`,`datetime`,`patient_signed_time`, `is_prn` FROM `med_logs` WHERE id ='" .$id . "'";
            
			if(sqlStatement($query)){
				log_user_event('eMAR - Administrative Popup', 'Inserted Into med_logs For Id:'.$id, $_SESSION['authUserID']);
			}else{
				log_user_event('eMAR - Administrative Popup', 'Failed to Insert into med_logs For Id:'.$id, $_SESSION['authUserID']);
			}
			
            $res = sqlStatement('SELECT MAX(id) as id FROM med_logs');
            $row = sqlFetchArray($res);
            $id = $row['id'];
        }
        $update_date= date('m-d-Y H:i:s');
        $pain_scale = '';
        
        $upd_qry = "UPDATE `med_logs` SET ";
        if (isset($_REQUEST['pain_sacle'][$id][0])) {
            $pain_scale = $_REQUEST['pain_sacle'][$id][0];
        
            if ($pain_scale > 0 && $pain_scale < 4) {
                $pain_scalef = 'mild';
            } elseif ($pain_scale >= 4 && $pain_scale < 6) {
                $pain_scalef = 'moderate ';
            } elseif ($pain_scale >= 6 && $pain_scale < 9) {
                $pain_scalef = 'high';
            } elseif ($pain_scale >= 9 && $pain_scale <= 10) {
                $pain_scalef = 'severe';
            } else {
                $pain_scalef = 'No Pain';
            }
            
            $pain_scale = " `pain_scale`='$pain_scalef' ";
        } 
                
            if($action == '1'){
                $upd_qry .= " `did_administer`= 1,`administered_note`='$note', `status` = 'Administered',`update_time`='$update_date',";
            }else if($action == '2'){
                $upd_qry .= " `did_refused`= 1, `administered_note` = '$note', `status` = 'Refused',`update_time`='$update_date',";
            }else if($action == '3'){
                $upd_qry .= " `did_administer`= 1, `administered_note` = '$note',`status` = 'Administered Late',`update_time`='$update_date',";
            }else if($action == '4'){
                $upd_qry .= "  `administered_note` = '$note',`status` = 'Held',`update_time`= '$update_date',";
            }

            $upd_qry .= " `administered_by`='$user_name',`glucose_reading`='$glucose_reading' ";
            if($pain_scale != ''){
                $upd_qry .= "  , $pain_scale " ;
            }
            $upd_qry .= "  WHERE `id`=$id" ;
       
			if($upd_res = sqlStatement($upd_qry)){
				log_user_event('eMAR - Administrative Popup', 'Updated med_logs For Id:'.$id, $_SESSION['authUserID']);
			}else{
				log_user_event('eMAR - Administrative Popup', 'Failed to Updated med_logs For Id:'.$id, $_SESSION['authUserID']);
			}
		//header('Location: '.$_SERVER['PHP_SELF']);
    echo "<script type='text/javascript'>$(function () { dlgclose(); }); </script>";
        
    }

}

//poop up med administer end

$pid = $GLOBALS['pid'];
$encounter=$_SESSION['encounter'];

$patient_status = get_patient_status($pid);
$status_pat = $patient_status['status'];

$providers = [];

$sql = 'SELECT id, fname, lname FROM users';
$res = sqlStatement($sql);
while($row = sqlFetchArray($res)){
	$providers[$row['id']] = $row;
}


$url_webroot = $GLOBALS['webroot'];
?>


<body>
   
<h3>Current Medications</h3>
<div id="print_links">
    <table width="100%">
        <tr>
            <td align="left">
                <button  class="small btn <?php echo $action_btn_class; ?> " id="btn_medlogs_submit" disabled="true"  onClick="submitForm();"><?= $action_btn; ?></button>
            </td>
        <td align="right">
                <table>
                <tr>
                    <td>
                        <button  class="small btn btn-default" onClick="Check();">Check All</button>
                        <button class="small btn btn-default" onClick="Uncheck();">Clear All</button>
                    </td>
                </tr>
                </table>
            </td>
        </tr>
        </table>
        </div>
                <form id="administrate_mdes_form" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>/interface/eMAR/index.php" method="post" >
                <input type="hidden" name="multimeds" id="multimeds" value="" />
                <input type="hidden" name="action" id="action" value="<?php echo $_GET['action']; ?>" />
                    <table id="" class="table e_MAR_tb_prescription table-striped">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Drug</th>
                                <th>Med Time</th>
								<th>Last Administered</th>
                                <th>Warning</th>
                                <th>Pain Scale</th>
                                <th>Glucose Reading</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(isset($_GET['medlogs']) && $_GET['medlogs'] != ''){
                                $meds =  explode(":",$_GET['medlogs']);
                                $medlogs =  implode(",",array_filter($meds));
                            
                            $pain_medscale = array("Tramadol", "gabapentin", "Norco", "ibuprofen", "oxycodone", "Acetaminophen", "Hydrocodone", "Dilaudid", "Tylenol", "Hydrocodoneacetaminophen", "methadone", "buprenorphine", "OxyContin", "Percocet", "Buprenex", "Celebrex", "Paracetamol", "naproxen", "ketorolac", "Ultram", "diclofenac", "acetaminophen/oxycodone", "lDemerol", "Nucynta", "aspirin", "Voltaren Arthritis Pain Ge", "hydromorphone", "Roxicodone", "Aleve", "acetaminophen/codeine",  "Advil", "Celecoxib", "codeine", "Diclofenac", "Tylenol Arthritis Pain", "Voltaren", "Motrin", "meloxicam", "hydroxyzine", "lidocaine", "nortriptyline", "acetaminophen/tramadol", "hydrocodone/ibuprofen", "pregabalin", "Arthritis Pain", "AspirinBayer", "Duloxetin", "Advil Liquid Gel");

                           $sql_query_current = "select med_logs.prescription_id,med_logs.id,med_logs.provider_id, med_logs.note,med_logs.warning_txt, patient_data.fname, patient_data.lname, `date_added`, `drug`, (SELECT title FROM `list_options` where list_id = 'drug_form'and option_id = `form` and activity = 1) as drug_form, `dosage`, `size`, (SELECT title FROM `list_options` where list_id = 'drug_units'and option_id = `unit` and activity = 1) as drug_units,(select update_time from med_logs where pid = '".$pid."' and prescription_id=`prescription_id` and did_administer = 1 order by update_time desc LIMIT 1) as last_administered, (SELECT title FROM `list_options` where list_id = 'drug_route'and option_id = `route` and activity = 1) as drug_route, (SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `interval` and activity = 1) as drug_interval, `med_time`, `administered_by`, `did_administer`, `administered_note`, `patient_signed`, `patient_signed_time`,`start_date`,`pain_scale`, `interval`, med_logs.`is_prn` ".
                            "FROM med_logs, patient_data ". 
                            "WHERE  med_logs.active = 1 AND med_logs.did_refused != 1  AND   (med_logs.`administered_note` <> 'Held' OR med_logs.`administered_note` IS NULL ) AND patient_data.pid=med_logs.patient_id  AND patient_data.pid='" . $pid . "' AND ".
                            "((med_logs.med_time < NOW() + INTERVAL 2 HOUR AND med_logs.med_time > NOW() - INTERVAL 2 HOUR AND med_logs.did_administer != 1) OR ((`interval` IN(17, 18) OR is_prn = 1) AND med_logs.did_administer != 1 ))  AND med_logs.id IN (".$medlogs.") " ;

                            /**
                             * @action 1: Administrate, 2: Refused, 3: Late, 4 Held
                             */
                            if (!empty($action)) {
                                switch ($action) {
                                        case '2': 
                                        case '3':
                                        case '4': $sql_query_current .= "  AND `interval` NOT IN (17,18) AND (med_logs.`is_prn` <> '1' OR med_logs.`is_prn` IS NULL )"; 
                                                    break;
                                }
                            }
                            // $resultset = mysqli_query($conn, $sql_query) or die("database error:" . mysqli_error($conn));
                            $res_current = sqlStatement($sql_query_current);
                            // if(count(sqlFetchArray($res_current)) > 0){
                            while ($current_row = sqlFetchArray($res_current)) {
								$matches = []; $matches1 = [];
                                $searchword = explode(" ", $current_row['drug']);
                                foreach ($pain_medscale as  $string) {
                                    foreach ($searchword as $value) {
                                        if (stripos($string, $value) !== FALSE)
                                            $matches[] = $string;
                                    }
                                }
                                foreach ($searchword as $value) {
                                    if (stripos($value,'insulin') !== FALSE)
                                        $matches1[] = $value;
                                }
								
								if ($current_row['interval'] == 18) {
                                    $class = 'stat_does_med';
                                } else if ($current_row['is_prn'] || $current_row['interval'] == 17) {
                                    $class = 'PRN_med';
								}else {
                                    $class = "";
                                }
								
								$prescription_id=$current_row['prescription_id'];
								$sql_query_current2 ="select update_time from med_logs where patient_id = $pid and prescription_id=$prescription_id and did_administer = 1 order by update_time desc LIMIT 1";
								
								$res_current2 = sqlStatement($sql_query_current2);
								$current_row2 = sqlFetchArray($res_current2);
								
                            ?>

                                <tr id="<?php echo $current_row['id']; ?>" class="<?= $class ?>">
                                
                                <!-- Changes by Latha -->
                                <td>
                                    
                                    <input class="check_list" id="check_list" type="checkbox" checked="checked" value="<?php echo $current_row['id']; ?>"  onclick="changeLinkHref('multimeds',this.checked, this.value);" >
                                </td>
                                <!-- End changes by Latha -->
                                    <td width="22%" style="color: #0d819c;">
										<h6><?php echo $current_row['drug'] ?></h6>
																														<?= $current_row['dosage']; ?>-<?= $current_row['drug_form']; ?>, <?= $current_row['drug_route']; ?>,  <?= $current_row['drug_interval']?> - <strong style="font-size:13px;"><?= $current_row['size']; ?> <?= $current_row['drug_units']; ?></strong>

									
										<?php if($current_row['interval'] != 18 && $current_row['is_prn'] || $current_row['interval'] == 17): ?>
																	<p class=" py-1 " style="color:darkblue; "> (P.R.N) When Necessary</p>
									<?php endif; ?>
										<p class="mt-2"><span class="provider_name"><?= $providers[$current_row['provider_id']]['fname']; ?> <?= $providers[$current_row['provider_id']]['lname']; ?> </span>:  <?php if($current_row['note']): ?><span  class="p-1" style="background:#F5EDDC; color: darkblue;word-wrap: anywhere"><?= $current_row['note']; ?></span><?php endif; ?> </p>
									</td> 
                                    <td style="color: red;"><?= date("m-d-Y H:i:s", strtotime($current_row['med_time'])); ?></td>
									
									 <td style="color: green;"><?= $current_row2['update_time']; ?></td>

                                    <td style="color: red;word-wrap: anywhere"><?php echo $current_row['warning_txt']; ?></td>
                                    <td>
                                        <?php
                                        if (sizeof($matches) > 0) { ?>
                                            <select class="pain_scale_sel" data-id="<?= $current_row['id'] ?>">
                                                <option value="0" selected>0</option>
                                                <?php for ($i = 1; $i <= 10; $i++) { ?>
                                                    <option value="<?= $i ?>"><?= $i ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (sizeof($matches1) > 0) { ?>
                                            <input type="text" class="glucose_reading" data-id="<?= $current_row['id'] ?>" value="">
                                        <?php } ?>
                                    </td>
                                    <td><input type="text" class="staff_note_enter" data-id="<?= $current_row['id'] ?>"></td>
									<td>
                             
                                            <input type="hidden" name="staff_note[<?= $current_row['id'] ?>][]" id="staff_note_paste_<?= $current_row['id'] ?>">
                                            <?php if (sizeof($matches) > 0) { ?>
                                                <input type="hidden" name="pain_sacle[<?= $current_row['id'] ?>][]" value="0" id="pain_scalef_<?= $current_row['id'] ?>">
                                            <?php } ?>
										                                            <input type="hidden" name="singleadmin" class="singleadmin" value="false">
                                            <input type="hidden" name="multiadmin" class="multiadmin" value="multiadmin">
                                           
                                            <input type="hidden" name="glucose_reading[<?= $current_row['id'] ?>][]" id="glucosef_<?= $current_row['id'] ?>">
                                            <input type="hidden" name="id[<?= $current_row['id'] ?>][]" value="<?php echo $current_row['id']; ?>">
                                            <input type="hidden" name="interval_id[<?= $current_row['id'] ?>][]" value="<?php echo $current_row['interval']; ?>">
                                            <input type="hidden" name="is_prn[<?= $current_row['id'] ?>][]" value="<?php echo $current_row['is_prn']; ?>">

                                            <?php  if($action == 2) { 
                                             echo '<input type="hidden" name="refused" class="refused" value="1">';
                                            } else if($action == 3) {
                                             echo '<input type="hidden" name="late" class="late" value="1">';
                                            } elseif($action == 4) {
                                             echo '<input type="hidden" name="held" class="held" value="1">'; 
                                            } ?>         
                                    </td>
                                </tr>
                            <?php  } ?> <?php } else { echo '<p class="text-danger" style="text-align:center;"><strong> No selection made </strong></p>'; }  ?>
                        </tbody>
                    </table>
					<p style="text-align:center";>Patient educated on all new and existing meds</p>
                    </form>  
</body>

<script>
    
function changeLinkHref(id,addValue,val) {
    var myRegExp = new RegExp(val + ":");
    if (addValue){ //add value to href
        if(document.getElementById(id) !== null)document.getElementById(id).value +=  val + ':';
    }
    else { //remove value from href
        if(document.getElementById(id) !== null)document.getElementById(id).value = document.getElementById(id).value.replace(myRegExp,'');
    }
    disable_btn_multimeds();
}

function changeLinkHrefAll(addValue, value) {
    changeLinkHref('multimeds', addValue, value);
}


function changeLinkHref_All(id,addValue,val) {
    var myRegExp = new RegExp(val + ":");
    if (addValue){ //add value to href
        if(document.getElementById(id) !== null)document.getElementById(id).value +=  val + ':';
    }
    else { //remove value from href
        if(document.getElementById(id) !== null)document.getElementById(id).value = document.getElementById(id).value.replace(myRegExp,'');
    }
}

function Check() {
    var chk = document.getElementsByClassName('check_list');
    var len=chk.length;
    if (len==undefined) {chk.checked=true;}
    else {
        //clean the checked id's before check all the list again
        var multimeds=document.getElementById('multimeds');
        if(multimeds!==null) {
            multimeds.value = document.getElementById('multimeds').value.substring(0, document.getElementById('multimeds').value.indexOf('=') + 1);
        }

        
        for (pr = 0; pr < chk.length; pr++){
            if($(chk[pr]).parents("tr.inactive").length==0)
                {
                    chk[pr].checked=true;
                    changeLinkHref_All('multimeds',true,chk[pr].value);
                }
        }
    }
    disable_btn_multimeds();
}

function Uncheck() {
    var chk = document.getElementsByClassName('check_list');
    var len=chk.length;
    if (len==undefined) {chk.checked=false;}
    else {
        for (pr = 0; pr < chk.length; pr++){
            chk[pr].checked=false;
            changeLinkHref_All('multimeds',false,chk[pr].value);
        }
    }
    disable_btn_multimeds();
}

function submitForm(){
    var form = document.getElementById("administrate_mdes_form");
    form.submit();
}

var CheckForChecks = function(chk) {
    // Checks for any checked boxes, if none are found than an alert is raised and the link is killed
    if (Checking(chk) == false) { return false; }
    return top.restoreSession();
};

function Checking(chk) {
    var len=chk.length;
    var foundone=false;

    if (len==undefined) {
            if (chk.checked == true){
                foundone=true;
            }
    }
    else {
        for (pr = 0; pr < chk.length; pr++){
            if (chk[pr].checked == true) {
                foundone=true;
            }
        }
    }
    if (foundone) {
        return true;
    } else {
        alert('Please select at least one med!');
        return false;
    }
}

$(function(){
  $(":checkbox:checked").each(function () {
      changeLinkHref('multimeds',this.checked, this.value);
  });
  disable_btn_multimeds();
});


$(function(){
    $("#multimeds").on("click", function() { return CheckForChecks(document.check_list); });
});

function disable_btn_multimeds(){
    var multimeds=$("#multimeds").val();
    var len=multimeds.length;
    if(multimeds===null || len === undefined || len === 0 || multimeds =='') {
        $("#btn_medlogs_submit").prop("disabled", true);
    }else{
        $("#btn_medlogs_submit").prop("disabled",false);
    }

}

$('.staff_note_enter').on('change', function() {
            id = $(this).data("id");
            var note = this.value;

            var idv = "staff_note_paste_" + id;
            document.getElementById(idv).value = note;
        });

        $('select.pain_scale_sel').on('change', function() {
            id = $(this).data("id");
            var note = this.value;

            var idv = "pain_scalef_" + id;
            document.getElementById(idv).value = note;
        });

        $('.glucose_reading').on('change', function() {
            
            id = $(this).data("id");
            //var id = $(this).attr('data-id');
            var note = this.value;
            
            var idv = "glucosef_" + id;
            document.getElementById(idv).value = note;
        });
</script>