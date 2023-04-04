<div class="row" id="verbal_orders" style="display: none;">

    <div class="col-md-12">
        <input type="hidden" id="pid" value="<?= $pid ?>">
        <input type="hidden" id="user_id" value=<?= $_SESSION['authUserID'] ?>>
        <div class="row py-2">
            <div class="col-6 col-md-6">
                <?php if(array_key_exists($_SESSION['authUserID'], $GLOBALS['approved_by'])) { ?>
                    <h4 class="text-bold" style="font-weight: 900;">My Orders</h4>
                <?php } else { ?>
                    <h4 class="text-bold" style="font-weight: 900;">My Verbal Orders</h4>
                <?php } ?>

            </div>
            <div class="col-6 col-md-6" id="signature_btn" style="display: none;">
                <div class="row">
                    <?php if (array_key_exists($_SESSION['authUserID'], $GLOBALS['approved_by'])) { ?>
                        <div class="col-12 ">
                            <a href="javascript:void(0);" class="btn-default" id="staff_sign">Approve and Sign</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <table id="" class="table e_MAR_tb-1" style="width:100%">
            <thead>
                <tr>
                    <th style="text-align:center;padding-left: 30px;"><input type="checkbox" id="my_select_all"></th>
                    <th>Drug</th>
                    <th>Start Date</th>
                    <th>Med Time</th>
                    <th>End Date</th>
                    <th>Meds Status</th>
                    <th>Updated On</th>
                    <!-- <th>Continue On DC</th> -->
                </tr>
            </thead>
            <tbody>
                <?php
                
                        $sql_query = "select prescriptions.id,prescriptions.is_prn,prescriptions.provider_id,prescriptions.active,prescriptions.order_set,prescriptions.verbal ,patient_data.fname, patient_data.lname, `date_added`, `drug`,`quantity`, `med_brought_in_id`, prescriptions.`date_modified`,prescriptions.`updated_by`,prescriptions.`cancelled_provider`,prescriptions.`cancelled_reason`,prescriptions.`cancelled_verbal`,
                        (SELECT title FROM `list_options` where list_id = 'drug_form'and option_id = `form` and activity = 1) as drug_form, 
                        `dosage`, `continue_on_discharge`,
                        `size`, 
                        (SELECT title FROM `list_options` where list_id = 'drug_units'and option_id = `unit` and activity = 1) as drug_units, 
                        (SELECT title FROM `list_options` where list_id = 'drug_route'and option_id = `route` and activity = 1) as drug_route, 
                        (SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `instruction` and activity = 1) as drug_instruction, 
                        (SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `interval` and activity = 1) as drug_interval, 
                        `datetime`, `start_date` , `note`,`interval`,
                        (SELECT max(med_time) FROM `med_logs` WHERE prescription_id = prescriptions.`id`) as enddate,
                        (SELECT max(end_date) FROM `med_logs` WHERE prescription_id = prescriptions.`id`) as enddate2,med_time,
                        (SELECT GROUP_CONCAT(med_time, ', ') FROM `med_logs` WHERE prescription_id = prescriptions.`id` GROUP BY prescription_id) as medtime2
                        from prescriptions, patient_data where patient_data.pid=prescriptions.patient_id and prescriptions.encounter = '" . $encounter . "' and prescriptions.`provider_id` = '" . $_SESSION['authUserID'] . "' and patient_data.pid='" . $pid . "' ORDER BY is_prn ASC, start_date ASC";
                try {
                    $res = sqlStatement($sql_query);

                    while ($developer = sqlFetchArray($res)) {
                        $qry_sign = "SELECT * FROM `medlogs_signature` JOIN `medlogs_signature_header` ON `medlogs_signature`.`header_id`=`medlogs_signature_header`.`header_id` WHERE `medlogs_signature`.`prescription_id`='" . $developer['id'] . "'";
                        $result_sign = sqlStatement($qry_sign);
                        $detail_sign = sqlFetchArray($result_sign);

                        if ($developer['active'] == 0) {
                            $class = 'inactive_med';
                        } else if ($developer['is_prn'] || $developer['interval'] == 17) {
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

                        $orderset_id = $developer['order_set'];
                        $sql_os = "SELECT `name` FROM `orderset` WHERE `id`= '$orderset_id'";
                        $res_os = sqlStatement($sql_os);
                        $row_os = sqlFetchArray($res_os);

                        if ($developer['is_prn'] == '1') {
                            $med_time_arr = explode(',', $developer['med_time']);
                        } else {
                            $med_time_arr = explode(',', $developer['medtime2']);
                        }
                        $Full_stay = "no";
                        if (($developer['is_prn'] || $developer['interval'] == 17) && $developer['quantity'] == NULL) {
                            $qty = "yes";
                            $Full_stay = "yes";
                        } else {
                            if ($developer['quantity'] > 0) {
                                $qty = "yes";
                            } else {
                                $qty = "no";
                            }
                        }
                ?>
                        <?php if ($qty == "yes" && count($med_time_arr) > 0 && !empty($med_time_arr[0])) { ?>
                            <tr id="<?php echo $developer['id']; ?>" class="<?= $class ?>">
                                <td class="<?= $class2 ?>">
                                    <?php if (!$detail_sign) { ?>
                                        <input type="checkbox" class="insert_staff_signature" data-id="<?= $developer['id'] ?>">
                                    <?php } else { ?>
                                        <span style="color: green;font-weight: 900;">Signed By <?= $providers[$detail_sign['staff_id']]['fname']; ?> <?= $providers[$detail_sign['staff_id']]['lname']; ?></span>
                                        <p><?= date('m-d-Y H:i:s', strtotime($detail_sign['signed_at'])) ?> </p>

                                    <?php } ?>
                                </td>

                                <td>
                                    <h6 class="drug_font"><?php echo $developer['drug'] ?> </h6>
                                    <?= $developer['dosage']; ?>-<?= $developer['drug_form']; ?>, <?= $developer['drug_route']; ?>, <?= $developer['drug_interval'] ?> - <strong style=" font-size:13px;"><?= $developer['size']; ?> <?= $developer['drug_units']; ?></strong>
                                    <br>
                                    <?php if ($developer['interval'] != 18 && $developer['is_prn'] || $developer['interval'] == 17) : ?>
                                        <p class="px-3 py-1 mt-2" style="color:darkblue;"> (P.R.N) When Necessary</p>
                                    <?php endif; ?>
                                    <p class='prov-note mb-0 my-3'><span class="provider_name"><?= $providers[$developer['provider_id']]['fname']; ?> <?= $providers[$developer['provider_id']]['lname']; ?> </span> :<span class="p-1" style="background:#F5EDDC; color: darkblue;word-wrap: anywhere"> <?php echo $developer['note']; ?></span></p>

                                    <span class="date_added_class"><?= date("m-d-Y H:i:s", strtotime($developer['date_added'])); ?></span>

                                    <br>
                                    <p style="color: coral;"><?= $row_os['name'] ?></p>
                                </td>

                                <td>
                                    <?= date("m-d-Y", strtotime($developer['start_date'])); ?>
                                </td>

                                <td>
                                    <?php
                                    if ($developer['is_prn'] == '1' && $developer['interval'] == 18) {
                                    } else {
                                    ?>
                                        <div class="dropdown">
                                            <span><u>Show Med times</u></span>
                                            <div class="dropdown-content">
                                                <?php
                                                if ($developer['is_prn'] == '1') {
                                                    $med_time_arr = explode(',', $developer['med_time']);
                                                    foreach ($med_time_arr as $med_time_data) {
                                                ?>
                                                        <p>
                                                            <?= $med_time_data ?>
                                                        </p>
                                                    <?php }
                                                } else {

                                                    $med_time_arr = explode(',', $developer['medtime2']);
                                                    $myArr = [];
                                                    foreach ($med_time_arr as $med_time_data) {
                                                        $data = explode(" ", $med_time_data);
                                                        if (!in_array($data[1], $myArr)) {
                                                            array_push($myArr, $data[1]);
                                                        }
                                                    }

                                                    foreach ($myArr as $val_item) {

                                                    ?>
                                                        <p>
                                                            <?= $val_item ?>
                                                        </p>
                                                <?php }
                                                } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </td>

                                <td>
                                    <?php if ($Full_stay == "yes") { ?>
                                        Full Stay
                                    <?php } else { ?>
                                        <?= ($developer['enddate2']) ? date("m-d-Y", strtotime($developer['enddate2'])) : date("m-d-Y", strtotime($developer['enddate'])); ?>
                                    <?php } ?>
                                </td>

                                <td>
                                    <?php echo ($developer['active'] == 1) ? 'Active' : 'Discontinued medication'; ?>
                                </td>

                                <td>
                                    <?= ($developer['date_modified']) ? date("m-d-Y H:i:s", strtotime($developer['date_modified'])) : ''; ?>: <span class="provider_name"><?= $providers[$developer['updated_by']]['fname']; ?> <?= $providers[$developer['updated_by']]['lname']; ?> </span>

                                    <br>
                                    <span>
                                        <?php if ($developer['active'] == 0) { ?>
                                            <span>
                                                Discontinued By <span class="provider_name"> <?= $providers[$developer['cancelled_provider']]['fname']; ?> <?= $providers[$developer['cancelled_provider']]['lname']; ?> </span> <?= $developer['cancelled_verbal'] ?>
                                            </span>
                                            <br>
                                            <span style="font-size: 13px;background: #a10b2e;color: #fff;">
                                                <?= $developer['cancelled_reason'] ?>
                                            </span>
                                        <?php } ?>
                                    </span>
                                </td>
                                
                                <!-- <td><input type="checkbox" name="continue_on_discharge" class="continue_on_discharge" data-val="<?= $developer['continue_on_discharge']; ?>" data-id="<?= $developer['id']; ?>" /></td> -->
                            </tr>
                        <?php } ?>

                <?php }
                } catch (exception $e) {
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="col-md-12">
        <div class="row">
            <div class="col-6 col-md-6"></div>
            <div class="col-6 col-md-6" id="signature_btn_do" style="display: none;">
                <div class="row">
                    <?php if (array_key_exists($_SESSION['authUserID'], $GLOBALS['approved_by'])) { ?>
                        <div class="col-12 ">
                            <a href="javascript:void(0);" class="btn-default" id="staff_sign_do">Approve and Sign</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <table id="" class="table e_MAR_tb">
            <thead>
                <tr>
                    <th style="text-align:center;padding-left: 30px;"><input type="checkbox" id="my_select_all_do"></th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Orders</th>
                    <th>Frequency</th>
                    <th>Note</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_query = "SELECT * FROM `doctors_order` where pid = '" . $pid . "' and encounter='" . $encounter . "' AND `provider_id` = '" . $_SESSION['authUserID'] . "' ";
                $res = sqlStatement($sql_query);
                while ($doctor_result = sqlFetchArray($res)) {

                    $qry_sign = "SELECT * FROM doctors_order_signature WHERE doctors_orders_id='" . $doctor_result['id'] . "'";
                    $result_sign = sqlStatement($qry_sign);
                    $detail_sign = sqlFetchArray($result_sign);

                    if ($doctor_result['from_orderset'] != 0) {
                        $class = "from_orderset_med";
                        $class2 = "orderset_border";
                    } else {
                        $class = "";
                        $class2 = "";
                    }

                ?>
                    <tr class="<?= $class ?>" id="<?php echo $doctor_result['id']; ?>" <?php if ($doctor_result['discontinued'] == 1) { ?> style="background-color: #D5DBDB;filter: blur(1px);" <?php } ?>>
                        <td class="<?= $class2 ?>" style="text-align:center;padding-left: 30px;">
                            <?php if (!$detail_sign) { ?>
                                <?php if ($doctor_result['discontinued'] != 1) { ?>
                                    <input type="checkbox" class="insert_staff_signature_do" data-id="<?= $doctor_result['id'] ?>">
                                <?php } ?>
                            <?php } else { ?>
                                <span style="color: green;font-weight: 900;padding-left: 10px;">Signed By <?php echo $providers[$detail_sign['signer_id']]['fname']; ?> <?php echo $providers[$detail_sign['signer_id']]['lname']; ?></span>
                                <p><?= date('m-d-Y H:i:s', strtotime($detail_sign['signed_at'])) ?></p>
                            <?php } ?>
                        </td>
                        <td><?= date("m-d-Y", strtotime($doctor_result['start_date'])); ?></td>
                        <td><?= date("m-d-Y", strtotime($doctor_result['end_date'])); ?></td>
                        <td><?php echo $doctor_result['vital_check']; ?></td>
                        <td><?php echo $doctor_result['frequency']; ?></td>
                        <td><?php echo $doctor_result['note']; ?></td>
                        <td>
                            <?php if ($doctor_result['discontinued'] == 1) { ?>
                                Discontinued By: <span class="provider_name"> <?php echo $providers[$doctor_result['provider_id']]['fname'];  ?> <?php echo $providers[$doctor_result['provider_id']]['lname'];  ?> </span> <br> <?php echo $doctor_result['verbal_order']; ?><br><span style="font-size: 13px;background: #a10b2e;color: #fff;"> <?= $doctor_result['discontinuation_reason']; ?> </span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="col-md-12">
        <hr>
        <div class="row py-2">

            <div class="col-6 col-md-6">
                <h4 class="text-bold" style="font-weight: 900;">Other Verbal Orders</h4>
            </div>
            <div class="col-6 col-md-6" id="other_signature_btn" style="display: none;">
                <div class="row">
                    <?php if (array_key_exists($_SESSION['authUserID'], $GLOBALS['approved_by'])) { ?>
                        <div class="col-12">
                            <a href="javascript:void(0);" class="btn-default" id="other_staff_sign">Approve and Sign</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <table id="" class="table e_MAR_tb-1" style="width:100%">
            <thead>
                <tr>
                    <th style="text-align:center"><input type="checkbox" id="other_select_all"></th>
                    <th>Drug</th>
                    <th>Start Date</th>
                    <th>Med Time</th>
                    <th>End Date</th>
                    <th>Meds Status</th>
                    <th>Updated On</th>
                    <!-- <th>Continue On DC</th> -->
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_query = "select prescriptions.id,prescriptions.is_prn,prescriptions.provider_id,prescriptions.active,prescriptions.verbal,prescriptions.order_set,patient_data.fname, patient_data.lname, `date_added`, `drug`,`quantity`, `med_brought_in_id`, prescriptions.`date_modified`,prescriptions.`updated_by`,prescriptions.`cancelled_provider`,prescriptions.`cancelled_reason`,prescriptions.`cancelled_verbal`,
										(SELECT title FROM `list_options` where list_id = 'drug_form'and option_id = `form` and activity = 1) as drug_form, 
										`dosage`, `continue_on_discharge`,
										`size`, 
										(SELECT title FROM `list_options` where list_id = 'drug_units'and option_id = `unit` and activity = 1) as drug_units, 
										(SELECT title FROM `list_options` where list_id = 'drug_route'and option_id = `route` and activity = 1) as drug_route, 
										(SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `instruction` and activity = 1) as drug_instruction, 
										(SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `interval` and activity = 1) as drug_interval, 
										`datetime`, `start_date` , `note`,`interval`,
                                        (SELECT max(med_time) FROM `med_logs` WHERE prescription_id = prescriptions.`id`) as enddate,
										(SELECT max(end_date) FROM `med_logs` WHERE prescription_id = prescriptions.`id`) as enddate2,med_time,
                                        (SELECT GROUP_CONCAT(med_time, ', ') FROM `med_logs` WHERE prescription_id = prescriptions.`id` GROUP BY prescription_id) as medtime2
                                        from prescriptions, patient_data where patient_data.pid=prescriptions.patient_id and prescriptions.encounter = '" . $encounter . "' and prescriptions.`provider_id` != '" . $_SESSION['authUserID'] . "' and  patient_data.pid='" . $pid . "' ORDER BY is_prn ASC, start_date ASC";
                try {
                    $res = sqlStatement($sql_query);

                    while ($developer = sqlFetchArray($res)) {
                        $qry_sign = "SELECT * FROM `medlogs_signature` JOIN `medlogs_signature_header` ON `medlogs_signature`.`header_id`=`medlogs_signature_header`.`header_id` WHERE `medlogs_signature`.`prescription_id`='" . $developer['id'] . "'";
                        $result_sign = sqlStatement($qry_sign);
                        $detail_sign = sqlFetchArray($result_sign);

                        if ($developer['active'] == 0) {
                            $class = 'inactive_med';
                        } else if ($developer['is_prn'] || $developer['interval'] == 17) {
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

                        $orderset_id = $developer['order_set'];
                        $sql_os = "SELECT `name` FROM `orderset` WHERE `id`= '$orderset_id'";
                        $res_os = sqlStatement($sql_os);
                        $row_os = sqlFetchArray($res_os);

                        if ($developer['is_prn'] == '1') {
                            $med_time_arr = explode(',', $developer['med_time']);
                        } else {
                            $med_time_arr = explode(',', $developer['medtime2']);
                        }
                        $Full_stay = "no";
                        if (($developer['is_prn'] || $developer['interval'] == 17) && $developer['quantity'] == NULL) {
                            $qty = "yes";
                            $Full_stay = "yes";
                        } else {
                            if ($developer['quantity'] > 0) {
                                $qty = "yes";
                            } else {
                                $qty = "no";
                            }
                        }
                ?>
                        <?php if ($qty == "yes" && count($med_time_arr) > 0 && !empty($med_time_arr[0])) { ?>
                            <tr id="<?php echo $developer['id']; ?>" class="<?= $class ?>">
                                <td class="<?= $class2 ?>">
                                    <?php if (!$detail_sign) { ?>
                                        <input type="checkbox" class="insert_other_staff_signature" data-id="<?= $developer['id'] ?>">
                                    <?php } else { ?>
                                        <span style="color: green;font-weight: 900;padding-left: 00px;">Signed By <?= $providers[$detail_sign['staff_id']]['fname']; ?> <?= $providers[$detail_sign['staff_id']]['lname']; ?></span>
                                        <p><?= date('m-d-Y H:i:s', strtotime($detail_sign['signed_at'])) ?></p>
                                    <?php } ?>
                                </td>
                                <td>
                                    <h6 class="drug_font"><?php echo $developer['drug'] ?> </h6>
                                    <?= $developer['dosage']; ?>-<?= $developer['drug_form']; ?>, <?= $developer['drug_route']; ?>, <?= $developer['drug_interval'] ?> - <strong style="font-size:13px;"><?= $developer['size']; ?> <?= $developer['drug_units']; ?></strong>
                                    <br>
                                    <?php if ($developer['interval'] != 18 && $developer['is_prn'] || $developer['interval'] == 17) : ?>
                                        <p class="px-3 py-1 mt-2" style="color:darkblue;"> (P.R.N) When Necessary</p>
                                    <?php endif; ?>
                                    <p class='prov-note mb-0 my-3'><span class="provider_name"><?= $providers[$developer['provider_id']]['fname']; ?> <?= $providers[$developer['provider_id']]['lname']; ?> </span> :<?php if ($developer['note'] != "") { ?> <span class="p-1" style="background:#F5EDDC; color: darkblue;word-wrap: anywhere"> <?php echo $developer['note']; ?></span><?php } ?></p>

                                    <span class="date_added_class"><?= date("m-d-Y H:i:s", strtotime($developer['date_added'])); ?></span>
                                    <br>
                                    <p style="color: coral;"><?= $row_os['name'] ?></p>

                                </td>
                                <td><?= date("m-d-Y", strtotime($developer['start_date'])); ?></td>
                                <td>
                                    <?php
                                    if ($developer['is_prn'] == '1' && $developer['interval'] == 18) {
                                    } else {
                                    ?>
                                        <div class="dropdown">
                                            <span><u>Show Med times</u></span>
                                            <div class="dropdown-content">
                                                <?php
                                                if ($developer['is_prn'] == '1') {
                                                    $med_time_arr = explode(',', $developer['med_time']);
                                                    foreach ($med_time_arr as $med_time_data) {
                                                ?>
                                                        <p>
                                                            <?= $med_time_data ?>
                                                        </p>
                                                    <?php }
                                                } else {
                                                    $med_time_arr = explode(',', $developer['medtime2']);
                                                    $myArr = [];
                                                    foreach ($med_time_arr as $med_time_data) {
                                                        $data = explode(" ", $med_time_data);
                                                        if (!in_array($data[1], $myArr)) {
                                                            array_push($myArr, $data[1]);
                                                        }
                                                    }

                                                    foreach ($myArr as $val_item) {

                                                    ?>
                                                        <p>
                                                            <?= $val_item ?>
                                                        </p>
                                                <?php }
                                                } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($Full_stay == "yes") { ?>
                                        Full Stay
                                    <?php } else { ?>
                                        <?= ($developer['enddate2']) ? date("m-d-Y", strtotime($developer['enddate2'])) : date("m-d-Y", strtotime($developer['enddate'])); ?>
                                    <?php } ?>
                                </td>
                                <td><?php echo ($developer['active'] == 1) ? 'Active' : 'Discontinued medication'; ?></td>

                                <td><?= ($developer['date_modified']) ? date("m-d-Y H:i:s", strtotime($developer['date_modified'])) : ''; ?>: <span class="provider_name"><?= $providers[$developer['updated_by']]['fname']; ?> <?= $providers[$developer['updated_by']]['lname']; ?> </span>
                                    <br>
                                    <span>
                                        <?php if ($developer['active'] == 0) { ?>
                                            <span>
                                                Discontinued By <span class="provider_name"> <?= $providers[$developer['cancelled_provider']]['fname']; ?> <?= $providers[$developer['cancelled_provider']]['lname']; ?> </span> <?= $developer['cancelled_verbal'] ?>
                                            </span>
                                            <br>
                                            <span style="font-size: 13px;background: #a10b2e;color: #fff;">
                                                <?= $developer['cancelled_reason'] ?>
                                            </span>
                                        <?php } ?>
                                    </span>
                                </td>

                                <!-- <td><input type="checkbox" name="continue_on_discharge" class="continue_on_discharge" data-val="<?= $developer['continue_on_discharge']; ?>" data-id="<?= $developer['id']; ?>" /></td> -->
                            </tr>
                        <?php } ?>

                <?php }
                } catch (exception $e) {
                }
                ?>

            </tbody>
        </table>
    </div>

    <div class="col-md-12">
        <div class="row py-2">
            <div class="col-6 col-md-6">

            </div>
            <div class="col-6 col-md-6" id="other_signature_btn_do" style="display: none;">
                <div class="row">
                    <?php if (array_key_exists($_SESSION['authUserID'], $GLOBALS['approved_by'])) { ?>
                        <div class="col-12">
                            <a href="javascript:void(0);" class="btn-default" id="other_staff_sign_do">Approve and Sign</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <table id="" class="table e_MAR_tb">
            <thead>
                <tr>
                    <th style="text-align:center"><input type="checkbox" id="other_select_all_do"></th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Orders</th>
                    <th>Frequency</th>
                    <th>Note</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_query = "SELECT * FROM `doctors_order` WHERE pid = '" . $pid . "' AND encounter='" . $encounter . "' AND `provider_id` != '" . $_SESSION['authUserID'] . "' ";

                $res = sqlStatement($sql_query);
                while ($doctor_result = sqlFetchArray($res)) {

                    $qry_sign = "SELECT * FROM doctors_order_signature WHERE doctors_orders_id='" . $doctor_result['id'] . "'";
                    $result_sign = sqlStatement($qry_sign);
                    $detail_sign = sqlFetchArray($result_sign);

                    if ($doctor_result['from_orderset'] != 0) {
                        $class = "from_orderset_med";
                        $class2 = "orderset_border";
                    } else {
                        $class = "";
                        $class2 = "";
                    }
                ?>
                    <tr class="<?= $class ?>" id="<?php echo $doctor_result['id']; ?>" <?php if ($doctor_result['discontinued'] == 1) { ?> style="background-color: #D5DBDB;filter: blur(1px);" <?php } ?>>

                        <td class="<?= $class2 ?>" style="text-align:center;padding-left: 30px;">
                            <?php if (!$detail_sign) { ?>
                                <?php if ($doctor_result['discontinued'] != 1) { ?>
                                    <input type="checkbox" class="insert_other_staff_signature_do" data-id="<?= $doctor_result['id'] ?>">
                                <?php } ?>
                            <?php } else { ?>
                                <span style="color: green;font-weight: 900;padding-left: 10px;">Signed By <?php echo $providers[$detail_sign['signer_id']]['fname']; ?> <?php echo $providers[$detail_sign['signer_id']]['lname']; ?></span>
                                <p><?= date('m-d-Y H:i:s', strtotime($detail_sign['signed_at'])) ?></p>
                            <?php } ?>
                        </td>
                        <td><?= date("m-d-Y", strtotime($doctor_result['start_date'])); ?></td>
                        <td><?= date("m-d-Y", strtotime($doctor_result['end_date'])); ?></td>
                        <td><?php echo $doctor_result['vital_check']; ?></td>
                        <td><?php echo $doctor_result['frequency']; ?></td>
                        <td><?php echo $doctor_result['note']; ?></td>
                        <td>
                            <?php if ($doctor_result['discontinued'] == 1) { ?>
                                Discontinued By: <span class="provider_name"> <?php echo $providers[$doctor_result['provider_id']]['fname'];  ?> <?php echo $providers[$doctor_result['provider_id']]['lname'];  ?> </span> <br> <?php echo $doctor_result['verbal_order']; ?><br><span style="font-size: 13px;background: #a10b2e;color: #fff;"> <?= $doctor_result['discontinuation_reason']; ?> </span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>