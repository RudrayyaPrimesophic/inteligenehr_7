<div class="row" id="ciwa" style="display: none;">
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
                    <th>CIWA Total Score</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_query = "SELECT form_ciwa_cust.id,form_ciwa_cust.date,form_ciwa_cust.datentime,form_ciwa_cust.pid,form_ciwa_cust.bp_systolic,form_ciwa_cust.bp_diastolic,form_ciwa_cust.temparature_pulse,form_ciwa_cust.pulse,form_ciwa_cust.respirations,form_ciwa_cust.o2_saturation,form_ciwa_cust.total_ciwa_score FROM `form_ciwa_cust` JOIN `forms` ON forms.form_id=form_ciwa_cust.id where form_ciwa_cust.pid = '" . $pid . "' AND forms.encounter='" . $encounter . "' order by date DESC";
                $res = sqlStatement($sql_query);
                while ($ciwa_result = sqlFetchArray($res)) {
                ?>
                    <tr>
                        <td><?= ($ciwa_result['datentime']) ? date("m-d-Y H:i:s", strtotime($ciwa_result['datentime'])) : ''; ?></td>
                        <td> <?php echo $ciwa_result['id']; ?></td>
                        <td <?php if ($ciwa_result['bp_systolic'] <= 80 || $ciwa_result['bp_systolic'] >= 160) echo "style='color:red'";  ?>><?php echo $ciwa_result['bp_systolic']; ?></td>
                        <td <?php if ($ciwa_result['bp_diastolic'] <= 60 || $ciwa_result['bp_diastolic'] >= 100) echo "style='color:red'";  ?>><?php echo $ciwa_result['bp_diastolic']; ?></td>
                        <td <?php if ($ciwa_result['temperature'] <= 96.1 || $ciwa_result['temperature'] >= 100.4) echo "style='color:red'";  ?>><?php echo $ciwa_result['temparature_pulse']; ?></td>
                        <td <?php if ($ciwa_result['pulse'] <= 60 || $ciwa_result['pulse'] >= 120) echo "style='color:red'";  ?>><?php echo $ciwa_result['pulse']; ?></td>
                        <td <?php if ($ciwa_result['respirations'] <= 10 || $ciwa_result['respirations'] >= 18) echo "style='color:red'";  ?>><?php echo $ciwa_result['respirations']; ?></td>
                        <td <?php if ($ciwa_result['o2_saturation'] <= 95 || $ciwa_result['o2_saturation'] >= 101) echo "style='color:red'";  ?>><?php echo $ciwa_result['o2_saturation']; ?></td>
                        <td><?php echo $ciwa_result['total_ciwa_score']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>