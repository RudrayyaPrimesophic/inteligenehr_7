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


$sql_query = "select prescriptions.id,prescriptions.is_prn,prescriptions.provider_id,prescriptions.active, patient_data.fname, patient_data.lname, `date_added`, `drug`,`quantity`, `med_brought_in_id`, prescriptions.`date_modified`,
										(SELECT title FROM `list_options` where list_id = 'drug_form'and option_id = `form` and activity = 1) as drug_form, 
										`dosage`, `continue_on_discharge`,
										`size`, 
										(SELECT title FROM `list_options` where list_id = 'drug_units'and option_id = `unit` and activity = 1) as drug_units, 
										(SELECT title FROM `list_options` where list_id = 'drug_route'and option_id = `route` and activity = 1) as drug_route, 
										(SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `instruction` and activity = 1) as drug_instruction, 
										(SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `interval` and activity = 1) as drug_interval, 
										`datetime`, `start_date` , `note`, `med_time` as pmed_time,
                                        (SELECT max(med_time) FROM `med_logs` WHERE prescription_id = prescriptions.`id`) as enddate,
                                        (SELECT GROUP_CONCAT(med_time, ', ') FROM `med_logs` WHERE prescription_id = prescriptions.`id`   AND med_time >= '". $date_from ."' AND med_time <= '". $to_date ."'  GROUP BY prescription_id) as medtime
                                        from prescriptions, patient_data where patient_data.pid=prescriptions.patient_id and patient_data.pid='" . $pid . "' ORDER BY start_date ASC";


$res = sqlStatement($sql_query);
$medicine_list = ["Quetiapine", "Seroquel", "Olanzapine", "Zyprexa", "Risperidone", "Risperdal", "Aripiprazole", "Abilify", "Ziprasidone", "Geodon", "Chlorpromazine", "Thorazine", "Lurasidone", "Latuda", "Paliperidone", "Invega"];
while($row = sqlFetchArray($res)){
	?> 
<?php
	 if ($row['quantity'] > 0 && ($row['medtime'] != '' && $row['medtime'] != ',')) { ?>
                                <tr id="<?php echo $row['id']; ?>"<?php if ($row['med_brought_in_id'] != '') echo "style='background-color:#cccaca'";  ?> <?= ($row['is_prn'] == 1)? 'style="background:#FED8B1"': ''; ?>>
									<td> <h6><?php echo $row['drug'] ?> </h6>
										<?= $row['dosage']; ?>-<?= $row['drug_form']; ?>, <?= $row['drug_route']; ?>,  <?= $row['drug_interval']?> - <strong style="font-size:13px;"><?= $row['size']; ?> <?= $row['drug_units']; ?></strong><br>
										<?php if($row['is_prn'] || $row['interval'] == 17 || $row['interval'] == 18 ): ?>
									<p class=" py-1" style="color:darkblue;"> (P.R.N) When Necessary</p>
									<?php endif; ?>
										<p class="mt-2"><span class="provider_name"><?= $providers[$row['provider_id']]['fname']; ?> <?= $providers[$row['provider_id']]['lname']; ?> </span> : <?php if($row['note']): ?><span  class="p-2" style="background:#F5EDDC; color: darkblue; font-size: 13px"><?= $row['note']; ?></span><?php endif; ?> </p>
										
										<span class="date_added_class"><?= date("m-d-Y H:i:s", strtotime($row['date_added'])); ?></span>
									
									</td>
                                    <td><?= date("m-d-Y", strtotime($row['start_date'])); ?></td>
                                    <td>
                                        <?php
                                         $med_time_arr = explode(',',$row['pmed_time']); 
                                         foreach($med_time_arr as $med_time_data){
                                            echo   $med_time_data.'<br>';
											 
                                         }
                                         ?>
                                    </td>
                                    <td><?= ($row['enddate']) ? date("m-d-Y", strtotime($row['enddate'])) : ''; ?></td>
                                    <td><?php echo ($row['active'] == 1) ? 'Active' : 'Inactive' ; ?></td>
                                    <td><?= ($row['date_modified'])? date("m-d-Y H:i:s", strtotime($row['date_modified'])): ''; ?></td>
									<td><input type="checkbox" name="continue_on_discharge" class="continue_on_discharge" data-val="<?= $row['continue_on_discharge']; ?>" data-id="<?= $row['id']; ?>" /></td>
									
                                </tr>
                            <?php } ?>

                            <?php
                            $drug = $row['drug'];
                            if (strposa($row['drug'], $medicine_list)) : ?>

                                <tr>
                                    <td colspan='11' class="text-center text-danger">
                                        <h5>Perform AIMS</h5>
                                    </td>
                                </tr>

                            <?php endif; 
	
}

?>
