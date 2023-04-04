<div class="row" id="vitals" style="display: none;">
    <div class="col-md-12 mb-4 text-right">
        <button class="btn-default <?= $disable_add_btns ?>" id="add_vitals">Add Vitals</button>
        <a class="btn-default " href="print_vitals.php" target="_blank">Print Vitals</a>
    </div>
    <div class="col-md-12">
        <table id="" class="table e_MAR_tb table-striped">
            <thead>
                <tr>
                    <th>Date and time of observation</th>
                    <th>Weight [lbs]</th>
                    <th>Height</th>
                    <th>Bps</th>
                    <th>Bpd</th>
                    <th>Temperature</th>
                    <th>Pulse</th>
                    <th>Respiration</th>
                    <th>O2 Saturation</th>
                    <th>BMI</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_query = " SELECT form_vitals.id,form_vitals.pid,form_vitals.bps,form_vitals.date,form_vitals.bpd,
                form_vitals.date_time,form_vitals.weight,form_vitals.height,temperature,form_vitals.temp_method,
                form_vitals.pulse,form_vitals.respiration,form_vitals.note,form_vitals.BMI,form_vitals.BMI_status,
                form_vitals.waist_circ,form_vitals.head_circ,form_vitals.oxygen_saturation 
                FROM `form_vitals` LEFT JOIN `forms` ON forms.form_id=form_vitals.id 
                WHERE   form_vitals.pid = '" . $GLOBALS['pid'] . "' 
                AND ((forms.encounter='" . $_SESSION['encounter'] . "' AND forms.is_error = 0)
                 OR form_vitals.encounter='" . $_SESSION['encounter'] . "' )  order by date DESC";
                $res = sqlStatement($sql_query);
                while ($vitals_result = sqlFetchArray($res)) {
                ?>
                    <tr>
                        <td><?= ($vitals_result['date_time']) ? date("m-d-Y H:i:s", strtotime($vitals_result['date_time'])) : ''; ?></td>
                        <td><?php echo $vitals_result['weight']; ?></td>
                        <td><?php echo $vitals_result['height']; ?></td>
                        <td <?php if ($vitals_result['bps'] <= 80 || $vitals_result['bps'] >= 160) echo "style='color:red'";  ?>><?php echo $vitals_result['bps']; ?></td>
                        <td <?php if ($vitals_result['bpd'] <= 60 || $vitals_result['bpd'] >= 100) echo "style='color:red'";  ?>><?php echo $vitals_result['bpd']; ?></td>
                        <td <?php if ($vitals_result['temperature'] <= 96.1 || $vitals_result['temperature'] >= 100.4) echo "style='color:red'";  ?>><?php echo $vitals_result['temperature']; ?></td>
                        <td <?php if ($vitals_result['pulse'] <= 60 || $vitals_result['pulse'] >= 120) echo "style='color:red'";  ?>><?php echo $vitals_result['pulse']; ?></td>
                        <td <?php if ($vitals_result['respiration'] <= 10 || $vitals_result['respiration'] >= 18) echo "style='color:red'";  ?>><?php echo $vitals_result['respiration']; ?></td>
                        <td <?php if ($vitals_result['oxygen_saturation'] <= 95 || $vitals_result['oxygen_saturation'] >= 101) echo "style='color:red'";  ?>><?php echo $vitals_result['oxygen_saturation']; ?></td>
                        <td><?php echo $vitals_result['BMI']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>


    </div>
</div>