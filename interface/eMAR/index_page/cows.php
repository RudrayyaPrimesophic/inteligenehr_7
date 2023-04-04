<div class="row" id="cows" style="display: none;">
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
                $sql_query = "SELECT form_cows_cust.id,form_cows_cust.date,form_cows_cust.dateandtime,form_cows_cust.pid,form_cows_cust.bp_systolic,form_cows_cust.bp_diastolic,form_cows_cust.temparature_pulse,form_cows_cust.pulse, form_cows_cust.respirations,form_cows_cust.o2_saturation,form_cows_cust.totalscore FROM `form_cows_cust` JOIN `forms` ON forms.form_id=form_cows_cust.id where form_cows_cust.pid = '" . $pid . "' AND forms.encounter='" . $encounter . "' order by date DESC";
                $res = sqlStatement($sql_query);
                while ($cows_result = sqlFetchArray($res)) {
                ?>
                    <tr>
                        <td><?= ($cows_result['dateandtime']) ? date("m-d-Y H:i:s", strtotime($cows_result['dateandtime'])) : ''; ?></td>
                        <td> <?php echo $cows_result['id']; ?></td>
                        <td <?php if ($cows_result['bp_systolic'] <= 80 || $cows_result['bp_systolic'] >= 160) echo "style='color:red'";  ?>><?php echo $cows_result['bp_systolic']; ?></td>
                        <td <?php if ($cows_result['bp_diastolic'] <= 60 || $cows_result['bp_diastolic'] >= 100) echo "style='color:red'";  ?>><?php echo $cows_result['bp_diastolic']; ?></td>
                        <td <?php if ($cows_result['temparature_pulse'] <= 96.1 || $cows_result['temparature_pulse'] >= 100.4) echo "style='color:red'";  ?>><?php echo $cows_result['temparature_pulse']; ?></td>
                        <td <?php if ($cows_result['pulse'] <= 60 || $cows_result['pulse'] >= 120) echo "style='color:red'";  ?>><?php echo $cows_result['pulse']; ?></td>
                        <td <?php if ($cows_result['respirations'] <= 10 || $cows_result['respirations'] >= 18) echo "style='color:red'";  ?>><?php echo $cows_result['respirations']; ?></td>
                        <td <?php if ($cows_result['o2_saturation'] <= 95 || $cows_result['o2_saturation'] >= 101) echo "style='color:red'";  ?>><?php echo $cows_result['o2_saturation']; ?></td>
                        <td><?php echo $cows_result['totalscore']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>