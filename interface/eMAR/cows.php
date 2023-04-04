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
							<th>Temperature</th>
							<th>Pulse</th>
							<th>Respiration</th>
							<th>O2 saturation</th>
							<th>COWS TOTAL SCORE</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$sql_query = "SELECT id,date,pid,bp_systolic,bp_diastolic, temparature_pulse,pulse, respirations,o2_saturation, totalscore FROM `form_cows_cust` where pid = '" . $pid . "' order by date DESC";
						$res = sqlStatement($sql_query);
						while ($cows_result = sqlFetchArray($res)) {
						?>
							<tr>
								<td><?php echo $cows_result['date']; ?></td>
								<td> <?php echo $cows_result['id']; ?></td>
								<td <?php if($cows_result['bp_systolic'] <= 80 || $cows_result['bp_systolic'] >= 160) echo "style='color:red'";  ?>><?php echo $cows_result['bp_systolic']; ?></td>
								<td <?php if($cows_result['bp_diastolic'] <= 60|| $cows_result['bp_diastolic'] >= 100) echo "style='color:red'";  ?>><?php echo $cows_result['bp_diastolic']; ?></td>
								<td <?php if($cows_result['temparature_pulse'] <= 96.1 || $cows_result['temparature_pulse'] >= 100.4) echo "style='color:red'";  ?>><?php echo $cows_result['temparature_pulse']; ?></td>
								<td <?php if($cows_result['pulse'] <= 60 || $cows_result['pulse'] >= 120) echo "style='color:red'";  ?> ><?php echo $cows_result['pulse']; ?></td>
								<td <?php if($cows_result['respirations'] <= 10 || $cows_result['respirations'] >= 18) echo "style='color:red'";  ?>><?php echo $cows_result['respirations']; ?></td>
								<td <?php if($cows_result['o2_saturation'] <= 95 || $cows_result['o2_saturation'] >= 101) echo "style='color:red'";  ?>><?php echo $cows_result['o2_saturation']; ?></td>
								<td><?php echo $cows_result['totalscore']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
	