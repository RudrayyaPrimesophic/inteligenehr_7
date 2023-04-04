<?php
include("header.php");

?>
<div class="col-md-12">
				<table id="" class="table e_MAR_tb table-striped">
					<thead>
						<tr>
							<th>Date</th>
							<th>ID</th>
							<th>Bp systolic</th>
							<th>Bp diastolic</th>
							<th>Temeprature</th>
							<th>Pulse</th>
							<th>Respiration</th>
							<th>O2 saturation</th>
							<th>CIWA Total Score</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$sql_query = "SELECT id,date,pid,	  bp_systolic,bp_diastolic, temparature_pulse,pulse, respirations,o2_saturation, total_ciwa_score FROM `form_ciwa_b` where pid = '" . $pid . "' order by date DESC";
						$res = sqlStatement($sql_query);
						while ($ciwa_result = sqlFetchArray($res)) {
						?>
							<tr>
								<td><?php echo $ciwa_result['date']; ?></td>
								<td> <?php echo $ciwa_result['id']; ?></td>
								<td <?php if($ciwa_result['bp_systolic'] <= 80 || $ciwa_result['bps'] >= 160) echo "style='color:red'";  ?>><?php echo $ciwa_result['bp_systolic']; ?></td>
								<td <?php if($ciwa_result['bpd'] <= 60 || $ciwa_result['bp_diastolic'] >= 100) echo "style='color:red'";  ?>><?php echo $ciwa_result['bp_diastolic']; ?></td>
								<td <?php if($ciwa_result['temperature'] <= 96.1 || $ciwa_result['temparature_pulse'] >= 100.4) echo "style='color:red'";  ?>><?php echo $ciwa_result['temparature_pulse']; ?></td>
								<td <?php if($ciwa_result['pulse'] <= 60 || $ciwa_result['pulse'] >= 120) echo "style='color:red'";  ?>><?php echo $ciwa_result['pulse']; ?></td>
								<td <?php if($ciwa_result['respirations'] <= 10 || $ciwa_result['respiration'] >= 18) echo "style='color:red'";  ?>><?php echo $ciwa_result['respirations']; ?></td>
								<td <?php if($ciwa_result['o2_saturation'] <= 95 || $ciwa_result['oxygen_saturation'] >= 101) echo "style='color:red'";  ?>><?php echo $ciwa_result['o2_saturation']; ?></td>
								<td><?php echo $ciwa_result['total_ciwa_score']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>