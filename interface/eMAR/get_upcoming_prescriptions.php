<?php
require_once(dirname(__FILE__) . "/../globals.php");
  require_once("$srcdir/options.inc.php");

use OpenEMR\Common\Csrf\CsrfUtils;

//if (!CsrfUtils::verifyCsrfToken($_GET["csrf_token_form"])) {
//    CsrfUtils::csrfNotVerified();
//}

$pid = $_GET['pid'];

$date_from = ($_GET['from_date']) ? $_GET['from_date'] : '01/01/2022';
if(strlen($date_from) == 10){
	$temp = explode('/', $date_from);
	$date_from = $temp[2].'-'.$temp[0].'-'.$temp[1]. " 00:00:00";
}
$to_date = ($_GET['to-date']) ? $_GET['to-date'] : '01/01/2040';
if(strlen($to_date) == 10){
	$temp = explode('/', $to_date);
	$to_date = $temp[2].'-'.$temp[0].'-'.$temp[1]. " 23:59:59";;
}

if(!$pid)
	exit;

$providers = [];

$sql = 'SELECT id, fname, lname FROM users';
$res = sqlStatement($sql);
while($row = sqlFetchArray($res)){
	$providers[$row['id']] = $row;
}


$sql_query = "select med_logs.id,med_logs.prescription_id,med_logs.provider_id, patient_data.fname, patient_data.lname, `date_added`, `drug`, 
						(SELECT title FROM `list_options` where list_id = 'drug_form'and option_id = `form` and activity = 1) as drug_form, 
						`dosage`,
						`size`,is_prn,
						(SELECT title FROM `list_options` where list_id = 'drug_units'and option_id = `unit` and activity = 1) as drug_units, 
						(SELECT title FROM `list_options` where list_id = 'drug_route'and option_id = `route` and activity = 1) as drug_route, 
						(SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `interval` and activity = 1) as drug_interval,`note`, 
						`med_time`, `administered_by`, `did_administer`, `administered_note`, `patient_signed`, `patient_signed_time`,`start_date`,`datetime` ,`pain_scale`,`glucose_reading`
						from med_logs, patient_data where patient_data.pid=med_logs.patient_id AND med_logs.active = 1  AND med_logs.did_administer != 1  AND med_logs.med_time > CURDATE()  and  patient_data.pid='" . $pid . "' AND med_time >= '". $date_from ."' AND med_time <= '". $to_date ."' ORDER BY is_prn, med_time ASC";


$res = sqlStatement($sql_query);

while($developer = sqlFetchArray($res)){
	?> 

<tr id="<?php echo $developer['id']; ?>" <?php if ($developer['is_prn'] || $developer['interval'] == 17 || $developer['interval'] == 18) : ?> style="background:#FED8B1;" <?php endif; ?>>
                                <td> <h6><?php echo $developer['drug'] ?></h6>
									<?= $developer['dosage']; ?>-<?= $developer['drug_form']; ?>, <?= $developer['drug_route']; ?>,  <?= $developer['drug_interval']?> - <strong style="font-size:13px;"><?= $developer['size']; ?> <?= $developer['drug_units']; ?></strong><br>
									<?php if($developer['is_prn'] || $developer['interval'] == 17 || $developer['interval'] == 18): ?>
									<p class=" py-1" style="color:darkblue;"> (P.R.N) When Necessary</p>
									<?php endif; ?>
									<br> <p class="mt-2"><span class="provider_name "><?= $providers[$developer['provider_id']]['fname']; ?> <?= $providers[$developer['provider_id']]['lname']; ?> </span>:<?php if($developer['note']): ?><span  class="p-2" style="background:#F5EDDC; color: darkblue;"><?= $developer['note']; ?></span><?php endif; ?></p>
								</td>
                                <td><?= date("m-d-Y H:i:s", strtotime($developer['med_time'])); ?></td>
								<?php
									$prescription_id = $developer['prescription_id'];
									$sql_la = "SELECT `update_time` FROM `med_logs` WHERE `prescription_id`='$prescription_id' AND `did_administer`='1' ORDER BY `updated_by` DESC LIMIT 1";
									$res_la = sqlStatement($sql_la);
									$row_la = sqlFetchArray($res_la);
									?>
								<td style="color: green;"><?= $row_la['update_time']; ?> </td>
                                <td><?php echo $developer['warning_txt']; ?></td>
                            </tr>

<?php
	
}

?>
