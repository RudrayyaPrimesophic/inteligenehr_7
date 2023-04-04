<div class="row" id="upcoming_med_logs" style="display: none;">
    <div class="col-md-12">
        <hr>
        <div class="row py-2">
            <div class="offset-4 col-1">
                <p class="my-2">Date From</p>
            </div>
            <div class="col-2"><input type="text" class="form-control dateonlypicker" id="up_from_date"></div>
            <div class="col-1">
                <p class="my-2">Date To</p>
            </div>
            <div class="col-2"><input type="text" class="form-control dateonlypicker" id="up_end_date"></div>
            <div class="col-2"><button class="btn btn-sm btn-primary" id="filter_upcoming_med">Filter</button></div>
        </div>
        <table id="" class="table  e_MAR_tb_prescription_upcoming">
            <thead>
                <tr>
                    <th>Drug </th>
                    <th>Med Time</th>
                    <th>Last Administered</th>
                    <th>Warning</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_query = "select med_logs.prescription_id,med_logs.warning_txt,med_logs.id,med_logs.provider_id,med_logs.order_set, patient_data.fname, patient_data.lname, `date_added`, `drug`, 
						(SELECT title FROM `list_options` where list_id = 'drug_form'and option_id = `form` and activity = 1) as drug_form, 
						`dosage`,
						`size`, `is_prn`, (select update_time from med_logs where pid = '" . $pid . "' and did_administer = 1 order by update_time desc LIMIT 1) as last_administered,
						(SELECT title FROM `list_options` where list_id = 'drug_units'and option_id = `unit` and activity = 1) as drug_units, 
						(SELECT title FROM `list_options` where list_id = 'drug_route'and option_id = `route` and activity = 1) as drug_route, 
						(SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `interval` and activity = 1) as drug_interval,`note`, `interval`,
						`med_time`, `administered_by`, `did_administer`, `administered_note`, `patient_signed`, `patient_signed_time`,`start_date`,`datetime` ,`pain_scale`,`glucose_reading`
						from med_logs, patient_data where patient_data.pid=med_logs.patient_id and `med_logs`.p_delete='0' AND med_logs.active = 1  AND med_logs.med_time > NOW() + INTERVAL 3 HOUR and med_logs.did_administer = 0 AND (CAST(med_logs.med_time AS DATE) > CAST(NOW() AS DATE) ) and patient_data.pid='" . $pid . "' ORDER By is_prn, med_time ASC";
                // echo $sql_query; 
                // $resultset = mysqli_query($conn, $sql_query) or die("database error:" . mysqli_error($conn));
                // (INTERVAL '3 DAYS' + NOW())
                $res = sqlStatement($sql_query);

                while ($developer = sqlFetchArray($res)) {
                    if ($developer['is_prn'] || $developer['interval'] == 17) {
                        $class = 'PRN_med';
                    } else if ($developer['interval'] == 18) {
                        $class = 'stat_does_med';
                    } else if ($developer['order_set'] != 0) {
                        $class = "from_orderset_med";
                    } else {
                        $class = "";
                    }

                    if ($developer['order_set'] != 0) {
                        $class2 = "orderset_border";
                    } else {
                        $class2 = "";
                    }

                    if ($developer['order_set'] != 0) {
                        $orderset_id = $developer['order_set'];
                        $sql_os = "SELECT `name` FROM `orderset` WHERE `id`= '$orderset_id'";
                        $res_os = sqlStatement($sql_os);
                        $row_os = sqlFetchArray($res_os);
                        $name = $row_os['name'];
                    } else {
                        $name = "";
                    }
                ?>
                    <tr id="<?php echo $developer['id']; ?>" class="<?= $class ?>">
                        <td class="<?= $class2 ?>">
                            <h6 class="drug_font"><?php echo $developer['drug'] ?></h6>
                            <?= $developer['dosage']; ?>-<?= $developer['drug_form']; ?>, <?= $developer['drug_route']; ?>, <?= $developer['drug_interval'] ?> - <strong style="font-size:13px;"><?= $developer['size']; ?> <?= $developer['drug_units']; ?></strong><br>
                            <?php if ($developer['interval'] != 18 && $developer['is_prn'] || $developer['interval'] == 17) : ?>
                                <p class=" py-1" style="color:darkblue;"> (P.R.N) When Necessary</p>
                            <?php endif; ?>
                            <br>
                            <p class="mt-2"><span class="provider_name "><?= $providers[$developer['provider_id']]['fname']; ?> <?= $providers[$developer['provider_id']]['lname']; ?> </span>:<?php if ($developer['note']) : ?><span class="p-1" style="background:#F5EDDC; color: darkblue;word-wrap: anywhere"><?= $developer['note']; ?></span><?php endif; ?></p>
                            <br>
                            <p style="color: coral;"><?= $name ?></p>
                        </td>
                        <td>
                            <?php
                            if ($developer['is_prn'] == '1' && $developer['interval'] == 18) { ?>
                                <?= date("m-d-Y", strtotime($developer['med_time'])); ?>
                            <?php    } else {
                            ?>
                                <?= date("m-d-Y H:i:s", strtotime($developer['med_time'])); ?>
                            <?php } ?>

                        </td>

                        <?php
                        $prescription_id = $developer['prescription_id'];
                        $sql_la = "SELECT `update_time` FROM `med_logs` WHERE `prescription_id`='$prescription_id' AND `did_administer`='1' ORDER BY `updated_by` DESC LIMIT 1";
                        $res_la = sqlStatement($sql_la);
                        $row_la = sqlFetchArray($res_la);
                        ?>
                        <td style="color: green;"><?= $row_la['update_time']; ?> </td>

                        <td style="word-wrap: anywhere"><?php echo $developer['warning_txt']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>