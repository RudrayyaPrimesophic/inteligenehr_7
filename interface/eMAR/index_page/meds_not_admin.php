<div class="row" id="meds_not_admin" style="display: none;">
    <div class="col-md-12">
        <table id="" class="table e_MAR_tb_prescription">
            <thead>
                <tr>
                    <th>Drug</th>
                    <th>Time Due</th>
                    <th>Updated Date</th>
                    <th>Warning</th>
                    <th>Pain Scale</th>
                    <th>Glucose Reading</th>
                    <th>Staff Note</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_query = "select med_logs.updated_by,med_logs.id,med_logs.provider_id,med_logs.status,med_logs.warning_txt,med_logs.update_time,patient_data.fname,med_logs.is_prn,med_logs.order_set,patient_data.lname, `date_added`, `drug`, 
						(SELECT title FROM `list_options` where list_id = 'drug_form'and option_id = `form` and activity = 1) as drug_form, 
						`dosage`,
						`size`, 
						(SELECT title FROM `list_options` where list_id = 'drug_units'and option_id = `unit` and activity = 1) as drug_units, 
						(SELECT title FROM `list_options` where list_id = 'drug_route'and option_id = `route` and activity = 1) as drug_route, 
						(SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `interval` and activity = 1) as drug_interval,`note`, `interval`,
						`med_time`, `administered_by`, `did_administer`, `administered_note`, `patient_signed`, `patient_signed_time`,`start_date`,`datetime` ,`pain_scale`,`glucose_reading`
						from med_logs, patient_data  where patient_data.pid=med_logs.patient_id and `med_logs`.p_delete='" . $p_delete . "' and (med_logs.status IN ('Held','Refused','Missed')) AND((med_logs.did_refused = 1 and `interval` <> 17 ) OR (med_logs.did_administer != 1 and `interval` != 17 AND med_logs.is_prn != 1 ))   and  patient_data.pid='" . $pid . "'";
                // $resultset = mysqli_query($conn, $sql_query) or die("database error:" . mysqli_error($conn));
                $res = sqlStatement($sql_query);
                while ($developer = sqlFetchArray($res)) {
                    if ($developer['interval'] == 18) {
                        $class = 'stat_does_med';
                    } else if ($developer['is_prn'] || $developer['interval'] == 17) {
                        $class = 'PRN_med';
                    } else if ($developer['order_set'] == 1) {
                        $class = "from_orderset_med";
                    } else {
                        $class = "";
                    }

                    if ($developer['order_set'] == 1) {
                        $class2 = "orderset_border";
                    } else {
                        $class2 = "";
                    }

                    $orderset_id = $developer['order_set'];
                    $sql_os = "SELECT `name` FROM `orderset` WHERE `id`= '$orderset_id'";
                    $res_os = sqlStatement($sql_os);
                    $row_os = sqlFetchArray($res_os);
                ?>
                    <tr id="<?php echo $developer['id']; ?>" class="<?= $class ?>">
                        <td class="<?= $class2 ?>">
                            <h6 class="drug_font"><?php echo $developer['drug'] ?></h6><br>
                            <?= $developer['dosage']; ?>-<?= $developer['drug_form']; ?>, <?= $developer['drug_route']; ?>, <?= $developer['drug_interval'] ?> - <strong style="font-size:13px;"><?= $developer['size']; ?> <?= $developer['drug_units']; ?></strong><br>
                            <br>
                            <p><span class="provider_name"><?= $providers[$developer['provider_id']]['fname']; ?> <?= $providers[$developer['provider_id']]['lname']; ?> </span>: <span style="word-wrap: anywhere;background:#F5EDDC; color: darkblue;font-size: 13px;"><?= $developer['note']; ?></span></p>

                            <br>
                            <p style="color: coral;"><?= $row_os['name'] ?></p>
                        </td>
                        <td><?= date("m-d-Y H:i:s", strtotime($developer['med_time'])); ?></td>
                        <td><?php echo $developer['update_time']; ?></td>
                        <td style="word-wrap: anywhere"><?php echo $developer['warning_txt']; ?></td>

                        <td><?php echo $developer['pain_scale']; ?></td>
                        <td><?php echo $developer['glucose_reading']; ?></td>
                        <td>
                            <?php if ($developer['administered_note'] == '') : ?>
                                <select class="staff_note" name="administered_note" data-id="<?php echo $developer['id']; ?>">
                                    <option value="" <?= $developer['administered_note'] == '' ? 'selected' : ''; ?>></option>
                                    <option value="Missed" <?= $developer['administered_note'] == 'Missed' ? 'selected' : ''; ?>>Missed</option>
                                    <option value="Held" <?= $developer['administered_note'] == 'Held' ? 'selected' : ''; ?>>Held</option>

                                </select>
                            <?php else : ?>
                                <p><?= $developer['administered_note'] ?></p>
                            <?php endif; ?>

                        <td <?php if ($developer['status']  == 'Refused') echo "style='background-color:orangered'";  ?><?php if ($developer['status']  == 'Held') echo "style='background-color:#545b62'";  ?><?php if ($developer['status']  == 'Missed') echo "style='background-color:#CBC3E3'";  ?>><?php echo $developer['status']; ?> Updated By : <span class="provider_name"><?= $providers[$developer['updated_by']]['fname']; ?> <?= $providers[$developer['updated_by']]['lname']; ?> </span> </td>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>