<?php
include("header.php");

?>

			<div class="col-md-12">
				<table id="" class="table e_MAR_tb table-striped">
					<thead>
						<tr>
							<th>Date</th>
							<th>Medication</th>
							<th>Dosage</th>
							<th>Hold</th>
							<th>Frequency</th>
							<th>Amount on Hand</th>
							<th>Last taken</th>
							<th>Prescribed by</th>
							<th>Logged By</th>
							<th>Amount Returned</th>
							<th>Date & Time</th>
							<th>Amount Destroyed</th>
							<th>Continue on discharge</th>
							<th>Witness</th>
							<th>&nbsp;</th>
							
						
						</tr>
					</thead>
					<tbody>
						<?php
						$sql_query = "SELECT form_med_reconcilation_brought_in.*,(SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `frequency`) as drug_interval FROM `form_med_reconcilation_brought_in` where pid='" . $pid . "'";
						$res = sqlStatement($sql_query);
						$i = 1;
						while($row = sqlFetchArray($res)){
							$id = $row['id'];
						?>

							<tr id="<?php echo $row['id']; ?>" <?php if($row['hold'] == '1') echo "style='background-color:#cccaca'";  ?> >
							  <td><?php echo $row['date'];?></td>
								<td><?php echo $row['medication']; ?></td>
								<td> <?php echo $row['dosage'] ?></td>
								<td><input type="hidden"  class="hid_hold" name="hold" value="0">
										<input class="form-check-input hid_hold" id="hold" type="checkbox" name="hold" value="1" <?php if($row['hold'] == '1') echo 'checked'; ?> disabled="disabled"></td>
								<td><?php echo $row['drug_interval']; ?></td>
								<td><?php echo $row['amnt_on_hand']; ?></td>
								<td><?php echo $row['last_taken']; ?></td>
								<td><?php echo $row['prescribe']; ?></td>
								<td><?php echo $row['logged_by']; ?></td>
								<td><?php echo $row['amt_returned']; ?></td>
								<td><?php echo $row['time']; ?></td>
								<td><?php echo $row['amount_destroyed']; ?></td>
								<td><input type="hidden"  class="hid_continue_discharge" name="continue_discharge" value="0">
										<input class="form-check-input hid_continue_discharge" id="continue_discharge" type="checkbox" name="continue_discharge" value="1" <?php if($row['continue_discharge'] == '1') echo 'checked'; ?> disabled="disabled"></td>
								<td><?php echo $row['witness']; ?></td>
							
			  <td><a class="btn update" href="#edit_meds<?php echo $id; ?>" data-sfid='"<?php echo $id;?>"' data-toggle="modal">Edit</a>
			  <div class="modal  preview-modal" id="edit_meds<?php echo $id; ?>" data-backdrop="" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Edit Meds Brought In</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: black;">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			
						
				<form action="<?= $webroot ?>/interface/eMAR/index.php" method="post">
				<input type="hidden" name="pid" value="<?= $pid ?>">
				<input type="hidden" name="edit_meds_broughtin" value="yes">
					<div class="modal-body">
						<table width="100%" class="table">
							<tbody>
								<?php
								$sql_query = "SELECT * FROM `form_med_reconcilation_brought_in` where id='" . $id . "'";
								$res2 = sqlStatement($sql_query);
								while($row2 = sqlFetchArray($res2)){
								?>


									<tr><td colspan=6 ><p class="p-tag">Medication<input type="hidden" name="id" id="id" value=<?php echo $row2['id']; ?>></p></td>
									<td colspan=6><input type="text" name="medication" id="medication" value="<?php echo $row2['medication']; ?>" class="form-control" readonly> </td></tr>
									<tr><td colspan=6 ><p class="p-tag">Dosage</p></td>
										<td colspan=6><input type="text" name="dosage" id="dosage" value="<?php echo $row2['dosage']; ?>" class="form-control" readonly></td></tr>
										<tr><td colspan=6 ><p class="p-tag">Hold</p></td>	
										<td colspan=6><input type="hidden"  class="hid_hold" name="hold" value="0">
										<input class="form-check-input hid_hold" id="hold" type="checkbox" name="hold" value="1" <?php if($row2['hold'] == '1') echo 'checked'; ?> ></td></tr>
										<tr><td colspan=6 ><p class="p-tag">Frequency</p></td>	
										<td colspan=6>
						
										<select name="frequency" class="form-control" readonly>
											<?php
											$sql_query ="SELECT title,option_id FROM `list_options` where list_id = 'drug_interval' and activity = '1'";
											$fq_res = sqlStatement($sql_query);
											while ($fq_result = sqlFetchArray($fq_res)) {
											?>
												<option value="<?php echo $fq_result['option_id'] ?>" <?php if($fq_result['option_id'] == $row2['frequency']) echo 'selected'?> ><?php echo $fq_result['title'] ?></option>
											<?php } ?>
										</select>
								<!-- <input type="text" class="form-control" name="frequency" value="" > -->
							</td></tr>
										<tr><td colspan=6 ><p class="p-tag">Amount on Hand</p></td>	
										<td colspan=6><input type="text" name="amnt_on_hand" id="amnt_on_hand" value="<?php echo $row2['amnt_on_hand'];  ?>" class="form-control" readonly></td></tr>
										<tr><td colspan=6 ><p class="p-tag">Last taken</p></td>	
										<td colspan=6><input type="text" name="last_taken" id="last_taken" value="<?php echo $row2['last_taken'];  ?>" class="form-control" readonly></td></tr>
										<tr><td colspan=6 ><p class="p-tag">Prescribed By</p></td>	
										<td colspan=6><input type="text" name="prescribe" id="prescribe" value="<?php echo $row2['prescribe'];  ?>" class="form-control" readonly></td></tr>
										<tr><td colspan=6 ><p class="p-tag">Logged By</p></td>	
										<td colspan=6><input type="text" name="logged_by" id="logged_by" value="<?php echo $row2['logged_by'];  ?>" class="form-control" readonly></td></tr>
										<tr><td colspan=6 ><p class="p-tag">Amount Returned</p></td>	
										<td colspan=6><input type="number" class="form-control" name="amt_returned" id="amt_returned" value="<?php echo $row2['amt_returned'];  ?>" ></td></tr>
										<tr><td colspan=6 ><p class="p-tag">Date and Time</p></td>	
										<td colspan=6><input type="datetime-local" name="time" id="time" class="form-control " value="<?php echo $row2['time'];  ?>"  ></td></tr>
										<tr><td colspan=6 ><p class="p-tag">Amount return</p></td>	
										<td colspan=6><input type="number" name="amount_destroyed" class="form-control" id="amount_destroyed" class="form-control" value="<?php echo $row2['amount_destroyed'];  ?>" ></td></tr>
										<tr><td colspan=6 ><p class="p-tag">continue discahrge</p></td>	
										<td colspan=6><input type="hidden"  class="hid_continue_discharge" name="continue_discharge" value="0">
										<input class="form-check-input hid_continue_discharge" id="continue_discharge" type="checkbox" name="continue_discharge" value="1" <?php if($row2['continue_discharge'] == '1') echo 'checked'; ?> ></td></tr>
									
										<tr><td colspan=6 ><p class="p-tag">Witness</p></td>	
										<td colspan=6><input type="text" name="witness" id="witness" value="<?php echo $row2['witness'];  ?>" class="form-control" readonly></td></tr>
                                   

										<?php $i++; } ?>	
							</tbody>
								</table>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Update</button>
					</div>
				</form>
			</div>
		</div>
	
	</div></td>	
			
	           </tr>
						<?php  } ?>
					</tbody>
				</table>
			</div>
		</div>


<?php 


?>

