<div class="row" id="meds_admin" style="display: none;">
    <div class="col-md-12">
        <table id="" class="table e_MAR_tb_prescription ">
            <thead>
                <tr>
                    <th>Drug</th>
                    <th>Administered Time</th>
                    <th>Warning</th>
                    <th>Pain Scale</th>
                    <th>Glucose Reading</th>
                    <th>Staff Note</th>
                    <th>Status</th>
                    <th>Staff</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_query = "select u.fname as userfname, u.lname as userlname, med_logs.id,med_logs.is_prn,med_logs.order_set,med_logs.status, med_logs.provider_id,med_logs.order_set,patient_data.fname, patient_data.lname, `date_added`, `drug`, 
											(SELECT title FROM `list_options` where list_id = 'drug_form'and option_id = `form` and activity = 1) as drug_form, 
											`dosage`, `warning_txt`,
											`size`, 
											(SELECT title FROM `list_options` where list_id = 'drug_units'and option_id = `unit` and activity = 1) as drug_units, 
											(SELECT title FROM `list_options` where list_id = 'drug_route'and option_id = `route` and activity = 1) as drug_route, 
											(SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `interval` and activity = 1) as drug_interval,`note`, 
											`med_time`, `administered_by`, `did_administer`, `interval`,`administered_note`, `patient_signed`, `patient_signed_time`,`start_date`,`datetime` ,`pain_scale`,`glucose_reading`
											from med_logs, patient_data, users u where patient_data.pid=med_logs.patient_id and did_administer = 1 AND `med_logs`.p_delete='" . $p_delete . "' AND patient_data.pid='" . $pid . "' AND u.username = med_logs.administered_by";
                // $resultset = mysqli_query($conn, $sql_query) or die("database error:" . mysqli_error($conn));
                $res = sqlStatement($sql_query);
                while ($developer = sqlFetchArray($res)) {
                    if ($developer['is_prn'] || $developer['interval'] == 17) {
                        $class = 'PRN_med';
                    } else if ($developer['interval'] == 18) {
                        $class = 'stat_does_med';
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
                            <h6 class="drug_font"><?php echo $developer['drug'] ?></h6>
                            <?= $developer['dosage']; ?>-<?= $developer['drug_form']; ?>, <?= $developer['drug_route']; ?>, <?= $developer['drug_interval'] ?> - <strong style="font-size:13px;"><?= $developer['size']; ?> <?= $developer['drug_units']; ?></strong> <br>
                            <p><span class="provider_name"><?= $providers[$developer['provider_id']]['fname']; ?> <?= $providers[$developer['provider_id']]['lname']; ?> </span>: <span style="word-wrap: anywhere;background:#F5EDDC; color: darkblue;font-size: 13px;"><?= $developer['note']; ?></span></p>
                            <br>
                            <p style="color: coral;"><?= $row_os['name'] ?></p>
                        </td>
                        <td><?= date("m-d-Y H:i:s", strtotime($developer['patient_signed_time'])); ?></td>
                        <td style="word-wrap: anywhere;"><?php echo $developer['warning_txt']; ?></td>

                        <td><?php echo $developer['pain_scale']; ?></td>
                        <td><?php echo $developer['glucose_reading']; ?></td>
                        <td><?php echo $developer['administered_note']; ?></td>
                        <td <?php if ($developer['status']  == 'Administered Late') echo "style='background-color:orange'";  ?>><?php echo $developer['status']; ?></td>
                        <td><?php echo $developer['userfname'] . " " . $developer['userlname']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>