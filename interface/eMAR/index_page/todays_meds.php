<div class="row" id="today_meds">
    <div class="col-md-12" style="margin-top:3px;">
        <hr>
        <h3 class="ml-4" style="color: #06576a !important;font-weight: 600;">Doctors Order</h3>
    </div>
    <div class="col-md-12">
        <table id="" class="table e_MAR_tb">
            <thead>
                <tr>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Order</th>
                    <th>Frequency</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $sql_query = "SELECT from_orderset,id,date,vital_check,frequency,start_date,end_date,note,DATE_FORMAT(date, '%Y-%m-%d') FROM `doctors_order` where (DATE(start_date) <= CURDATE() AND DATE(end_date) >= CURDATE()) and discontinued='0' and pid = '" . $pid . "' and p_delete='" . $p_delete . "' ";
                $res = sqlStatement($sql_query);

                while ($doctor_result = sqlFetchArray($res)) {
                    if ($doctor_result['from_orderset'] != 0) {
                        $class = "from_orderset_med";
                        $class2 = "orderset_border";
                    } else {
                        $class = "";
                        $class2 = "";
                    }
                ?>
                    <tr id="<?php echo $doctor_result['id']; ?>" class="<?= $class ?>">
                        <td class="<?= $class2 ?>"><?= date("m-d-Y", strtotime($doctor_result['start_date'])); ?></td>
                        <td><?= date("m-d-Y", strtotime($doctor_result['end_date'])); ?></td>
                        <td><?php echo $doctor_result['vital_check']; ?></td>
                        <td><?php echo $doctor_result['frequency']; ?></td>
                        <td><?php echo $doctor_result['note']; ?></td>
                    </tr>
                <?php
                } ?>
            </tbody>
        </table>
    </div>

    <div class="col-md-12" style="margin-top:3px;">
        <hr>
        <h3 class="ml-4" style="color: #06576a !important;font-weight: 600;">Current Meds</h3>
    </div>
    <?php if ($status_pat == 'on-hold') : ?>
        <h4 class="text-danger" style="text-align:center">Patient medication on hold</h4>

    <?php else : ?>
        <div class="col-md-12">

            <input type="hidden" name="multimeds" id="multimeds" value="" />

            <table id="" class="table e_MAR_tb_current_med">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Drug</th>
                        <th>Med Time</th>
                        <th>Last Administered</th>
                        <th>Warning</th>
                        <th>Pain Scale</th>
                        <th>Glucose Reading</th>
                        <th>Note</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pain_medscale = array("Tramadol", "gabapentin", "Norco", "ibuprofen", "oxycodone", "Acetaminophen", "Hydrocodone", "Dilaudid", "Tylenol", "Hydrocodoneacetaminophen", "methadone", "buprenorphine", "OxyContin", "Percocet", "Buprenex", "Celebrex", "Paracetamol", "naproxen", "ketorolac", "Ultram", "diclofenac", "acetaminophen/oxycodone", "lDemerol", "Nucynta", "aspirin", "Voltaren Arthritis Pain Ge", "hydromorphone", "Roxicodone", "Aleve", "acetaminophen/codeine",  "Advil", "Celecoxib", "codeine", "Diclofenac", "Tylenol Arthritis Pain", "Voltaren", "Motrin", "meloxicam", "hydroxyzine", "lidocaine", "nortriptyline", "acetaminophen/tramadol", "hydrocodone/ibuprofen", "pregabalin", "Arthritis Pain", "AspirinBayer", "Duloxetin", "Advil Liquid Gel");

                    $sql_query_current = "select med_logs.id,med_logs.start_date,med_logs.end_date,med_logs.quantity,med_logs.provider_id, med_logs.note,med_logs.warning_txt,med_logs.order_set, patient_data.fname, patient_data.lname, `date_added`, `drug`, (SELECT title FROM `list_options` where list_id = 'drug_form'and option_id = `form` and activity = 1) as drug_form, `dosage`, `size`, (SELECT title FROM `list_options` where list_id = 'drug_units'and option_id = `unit` and activity = 1) as drug_units, (SELECT title FROM `list_options` where list_id = 'drug_route'and option_id = `route` and activity = 1) as drug_route, (SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `interval` and activity = 1) as drug_interval, `med_time`, `administered_by`, `did_administer`, `administered_note`, `patient_signed`, `patient_signed_time`,`start_date`,`pain_scale`, `interval`, med_logs.`is_prn`, med_logs.`prescription_id` " .
                        "FROM med_logs, patient_data " .
                        "WHERE med_logs.active = 1 and `med_logs`.p_delete='" . $p_delete . "' and med_logs.did_administer = 0 AND  med_logs.did_refused != 1 AND   ( med_logs.status NOT IN ('Held','Late','Refused','Missed') OR med_logs.status IS NULL) AND patient_data.pid=med_logs.patient_id  AND patient_data.pid='" . $pid . "' AND " .
                        "((med_logs.med_time < NOW() + INTERVAL 2 HOUR  AND med_logs.did_administer != 1) OR ((`interval` IN(17) OR is_prn = 1) AND med_logs.did_administer != 1 AND CAST(med_logs.med_time AS DATE) <= CAST(NOW() AS DATE)) OR (`interval` IN(18) AND med_logs.did_administer != 1 AND CAST(med_logs.med_time AS DATE) <= CAST(NOW() AS DATE) ))  ORDER By is_prn ASC, med_time ASC";
                    // $resultset = mysqli_query($conn, $sql_query) or die("database error:" . mysqli_error($conn));
                    $res_current = sqlStatement($sql_query_current);
                    while ($current_row = sqlFetchArray($res_current)) {
                        $matches = [];
                        $matches1 = [];
                        $searchword = explode(" ", $current_row['drug']);
                        foreach ($pain_medscale as  $string) {
                            foreach ($searchword as $value) {
                                if (stripos($string, $value) !== FALSE)
                                    $matches[] = $string;
                            }
                        }
                        foreach ($searchword as $value) {
                            if (stripos($value, 'insulin') !== FALSE)
                                $matches1[] = $value;
                        }

                        if ($current_row['is_prn'] || $current_row['interval'] == 17) {
                            $class = 'PRN_med';
                        } else if ($current_row['interval'] == 18) {
                            $class = 'stat_does_med';
                        } else if ($current_row['order_set'] != 0) {
                            $class = "from_orderset_med";
                        } else {
                            $class = "";
                        }

                        if ($current_row['order_set'] != 0) {
                            $class2 = "orderset_border";
                        } else {
                            $class2 = "";
                        }

                        $time = strtotime(date('Y-m-d H:i:s'));
                        $administer_time_1hour = strtotime($current_row['med_time']) + 60 * 60;
                        $administer_time_2hour = strtotime($current_row['med_time']) + 60 * 60 * 2;
                        $administer_time = strtotime($current_row['med_time']);

                        $orderset_id = $current_row['order_set'];
                        $sql_os = "SELECT `name` FROM `orderset` WHERE `id`= '$orderset_id'";
                        $res_os = sqlStatement($sql_os);
                        $row_os = sqlFetchArray($res_os);

                        $date = date('Y-m-d');
                        $start_date = date('Y-m-d', strtotime($current_row['Start_date']));
                        $end_date = date('Y-m-d', strtotime($current_row['end_date']));
                    ?>

                        <?php
                        if ($current_row['end_date'] != NULL) {
                            $datess = "Yes";
                        } else {
                            $datess = "No";
                        }
                        ?>

                        <?php
                        if (strtotime($date) > strtotime($end_date) && ($current_row['is_prn'] || $current_row['interval'] == 17)) {
                            if ($datess === "Yes") {
                                $skip = 1;
                            } else {
                                $skip = 0;
                            }
                        } else {
                            $skip = 0;
                        }

                        if (($current_row['is_prn'] == '1' || $current_row['interval'] == 17) && $current_row['quantity'] == NULL) {
                            $qty = "yes";
                        } else {
                            if ($current_row['quantity'] > 0) {
                                $qty = "yes";
                            } else {
                                $qty = "no";
                            }
                        }
                        if ($skip == 0 && $qty == "yes") {
                        ?>

                            <tr id="<?php echo $current_row['id']; ?>" class="<?= $class ?>">

                                <!-- Changes by Latha -->
                                <td class="<?= $class2 ?>">
                                    <?php if ($current_row['is_prn'] == 1) { ?>
                                        <input class="check_list" id="check_list" type="checkbox" value="<?php echo $current_row['id']; ?>" data-prescription_id="<?= $current_row['prescription_id'] ?>" data-is_prn="<?= ($current_row['is_prn'] || $current_row['interval'] == 17 || $current_row['interval'] == 18) ? 1 : 0; ?>" onclick="changeLinkHref('multimeds',this.checked, this.value);">
                                    <?php } elseif ($time < $administer_time_1hour && $time < $administer_time_2hour) { ?>
                                        <input class="check_list" id="check_list" type="checkbox" value="<?php echo $current_row['id']; ?>" data-prescription_id="<?= $current_row['prescription_id'] ?>" data-is_prn="<?= ($current_row['is_prn'] || $current_row['interval'] == 17 || $current_row['interval'] == 18) ? 1 : 0; ?>" onclick="changeLinkHref('multimeds',this.checked, this.value);">
                                    <?php } ?>
                                </td>
                                <!-- End changes by Latha -->

                                <td width="22%" style="color: #0d819c;">
                                    <h6 class="drug_font"><?php echo $current_row['drug'] ?></h6>
                                    <?= $current_row['dosage']; ?>-<?= $current_row['drug_form']; ?>, <?= $current_row['drug_route']; ?>, <?= $current_row['drug_interval'] ?> - <strong style="font-size:13px;"><?= $current_row['size']; ?> <?= $current_row['drug_units']; ?></strong>

                                    <?php if ($current_row['is_prn'] == 1 || $current_row['interval'] == 17) : ?>
                                        <p class=" py-1 " style="color:darkblue; "> (P.R.N) When Necessary</p>
                                    <?php endif; ?>
                                    <p class="mt-2"><span class="provider_name"><?= $providers[$current_row['provider_id']]['fname']; ?> <?= $providers[$current_row['provider_id']]['lname']; ?> </span>: <?php if ($current_row['note']) : ?><span class="p-1" style="background:#F5EDDC; color: darkblue;word-wrap: anywhere;"><?= $current_row['note']; ?></span><?php endif; ?> </p>
                                    </br>
                                    <p style="color: coral;"><?= $row_os['name'] ?></p>


                                </td>

                                <td style="color: red;">
                                    <?php
                                    if ($current_row['is_prn'] == 1 && $current_row['interval'] == 18) {
                                        echo date("m-d-Y", strtotime($current_row['med_time']));
                                    } else {
                                        echo date("m-d-Y H:i:s", strtotime($current_row['med_time']));
                                    }
                                    ?>
                                </td>
                                <?php
                                $prescription_id = $current_row['prescription_id'];
                                $sql_la = "SELECT `update_time` FROM `med_logs` WHERE `prescription_id`='$prescription_id' AND `did_administer`='1' ORDER BY `id` DESC LIMIT 1";
                                $res_la = sqlStatement($sql_la);
                                $row_la = sqlFetchArray($res_la);
                                ?>
                                <td style="color: green;"><?= $row_la['update_time']; ?> </td>

                                <td style="color: red;word-wrap: anywhere;">
                                    <h6><?= $current_row['warning_txt']; ?> <?php $size_match = count($matches) ?></h6>
                                </td>
                                <td>
                                    <?php
                                    if (count($matches) > 0) { ?>
                                        <select class="pain_scale_sel" data-id="<?= $current_row['id'] ?>">
                                            <option value="0" selected>0</option>
                                            <?php for ($i = 1; $i <= 10; $i++) { ?>
                                                <option value="<?= $i ?>"><?= $i ?></option>
                                            <?php } ?>
                                        </select>
                                    <?php } ?>
                                </td>

                                <td>
                                    <?php
                                    if (sizeof($matches1) > 0) { ?>
                                        <input type="text" class="glucose_reading" data-id="<?= $current_row['id'] ?>" value="">
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($current_row['is_prn'] == 1) { ?>
                                        <input type="text" class="staff_note_enter" data-id="<?= $current_row['id'] ?>">
                                    <?php } else { ?>
                                        <?php if ($time > $administer_time_1hour && $time < $administer_time_2hour) { ?>
                                            <input type="text" class="staff_note_enter" data-id="<?= $current_row['id'] ?>" required style="border-color:red">
                                        <?php } elseif ($time > $administer_time_1hour && $time > $administer_time_2hour) { ?>
                                            <input type="text" class="staff_note_enter" data-id="<?= $current_row['id'] ?>" required style="border-color:red">
                                        <?php } elseif ($time < $administer_time_1hour && $time < $administer_time_2hour) { ?>
                                            <input type="text" class="staff_note_enter" data-id="<?= $current_row['id'] ?>">
                                        <?php } ?>
                                    <?php } ?>
                                </td>


                                <td>
                                    <form action="<?= $webroot ?>/interface/eMAR/index.php" method="post" id="admin_popup_<?= $current_row['id'] ?>">
                                        <input type="hidden" name="staff_note" id="staff_note_paste_<?= $current_row['id'] ?>">
                                        <?php if (sizeof($matches) > 0) { ?>
                                            <input type="hidden" name="pain_sacle" value="0" id="pain_scalef_<?= $current_row['id'] ?>">
                                        <?php } ?>
                                        <input type="hidden" name="glucose_reading" id="glucosef_<?= $current_row['id'] ?>">
                                        <input type="hidden" name="id" value="<?php echo $current_row['id']; ?>">
                                        <input type="hidden" name="interval_id" value="<?php echo $current_row['interval']; ?>">
                                        <input type="hidden" name="is_prn" value="<?php echo $current_row['is_prn']; ?>">
                                        <input type="hidden" name="singleadmin" class="singleadmin" value="false">
                                        <input type="hidden" name="refused" class="refused" value="0">
                                        <input type="hidden" name="late" class="late" value="0">
                                        <input type="hidden" name="held" class="held" value="0">
                                        <input type="hidden" name="missed" class="missed" value="0">


                                        <?php if ($current_row['is_prn'] == 1 || $current_row['interval'] == 17) { ?>

                                            <input type="button" value="Administer" class="btn_single_medlogs m-1 btn-sm btn btn-primary" onclick="current_med_administer('confirm', '<?= $current_row['id']; ?>', '<?= $current_row['prescription_id'] ?>', '<?= $current_row['is_prn'] ?><?= ($current_row['interval'] == 17) ? 1 : ''; ?>');" style="color:black" />

                                        <?php } else if ($current_row['interval'] == 18) { ?>

                                            <?php if ($time > $administer_time_1hour) { ?>

                                                <input type="button" value="Missed" class="btn_single_medlogs m-1 btn-sm btn" style="background-color:#CBC3E3;border-color:#CBC3E3;" onclick="current_med_administer('missed', '<?= $current_row['id']; ?>', '<?= $current_row['prescription_id'] ?>', '<?= $current_row['is_prn'] ?><?= ($current_row['interval'] == 17) ? 1 : ''; ?>');" style="color:black" />

                                            <?php } else { ?>

                                                <input type="button" value="Administer" class="btn_single_medlogs m-1 btn-sm btn btn-primary" onclick="current_med_administer('confirm', '<?= $current_row['id']; ?>', '<?= $current_row['prescription_id'] ?>', '<?= $current_row['is_prn'] ?><?= ($current_row['interval'] == 17) ? 1 : ''; ?>');" style="color:black" />

                                            <?php } ?>

                                            <input type="submit" value="Held" class="btn_single_medlogs m-1 btn-sm btn btn-secondary" onclick="current_med_administer('held','<?= $current_row['id'] ?>');" style="color:black" />

                                        <?php } else { ?>

                                            <?php if ($time > $administer_time_1hour && $time < $administer_time_2hour) { ?>

                                                <input type="button" value="Administer Late" class="btn_single_medlogs m-1 btn-sm btn btn-warning" onclick="current_med_administer('administed_late', '<?= $current_row['id']; ?>', '<?= $current_row['prescription_id'] ?>', '<?= $current_row['is_prn'] ?><?= ($current_row['interval'] == 17) ? 1 : ''; ?>');" style="color:black" />

                                                <?php if ($current_row['is_prn'] != 1 && $current_row['interval'] != 17 && $current_row['interval'] != 18) : ?>
                                                    <input type="submit" value="Held" class="btn_single_medlogs m-1 btn-sm btn btn-secondary" onclick="current_med_administer('held','<?= $current_row['id'] ?>');" style="color:black" />
                                                    <input type="submit" value="Refused" class="btn_single_medlogs m-1 btn-sm btn btn-danger" onclick="current_med_administer('refuse','<?= $current_row['id'] ?>');" style="color:black" />
                                                <?php endif; ?>

                                            <?php } elseif ($time > $administer_time_1hour && $time > $administer_time_2hour) { ?>

                                                <input type="button" value="Missed" class="btn_single_medlogs m-1 btn-sm btn" style="background-color:#CBC3E3;border-color:#CBC3E3;" onclick="current_med_administer('missed', '<?= $current_row['id']; ?>', '<?= $current_row['prescription_id'] ?>', '<?= $current_row['is_prn'] ?><?= ($current_row['interval'] == 17) ? 1 : ''; ?>');" style="color:black" />

                                                <?php if ($current_row['is_prn'] != 1 && $current_row['interval'] != 17 && $current_row['interval'] != 18) : ?>
                                                    <input type="submit" value="Held" class="btn_single_medlogs m-1 btn-sm btn btn-secondary" onclick="current_med_administer('held','<?= $current_row['id'] ?>');" style="color:black" />
                                                <?php endif; ?>

                                            <?php } elseif ($time < $administer_time_1hour && $time < $administer_time_2hour) { ?>

                                                <input type="button" value="Administer" class="btn_single_medlogs m-1 btn-sm btn btn-primary" onclick="current_med_administer('confirm', '<?= $current_row['id']; ?>', '<?= $current_row['prescription_id'] ?>', '<?= $current_row['is_prn'] ?><?= ($current_row['interval'] == 17) ? 1 : ''; ?>');" style="color:black" />

                                                <?php if ($current_row['is_prn'] != 1 && $current_row['interval'] != 17 && $current_row['interval'] != 18) : ?>
                                                    <input type="submit" value="Held" class="btn_single_medlogs m-1 btn-sm btn btn-secondary" onclick="current_med_administer('held','<?= $current_row['id'] ?>');" style="color:black" />
                                                    <input type="submit" value="Refused" class="btn_single_medlogs m-1 btn-sm btn btn-danger" onclick="current_med_administer('refuse','<?= $current_row['id'] ?>');" style="color:black" />
                                                <?php endif; ?>

                                            <?php } ?>

                                        <?php } ?>
                                    </form>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>

                    <?php  } ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    <div class="col-md-12" style="margin-top:3px;">
        <hr>
        <h3 class="ml-4" style="color: #06576a !important;font-weight: 600;">Upcoming Meds Today</h3>
    </div>
    <div class="col-md-12 mt-3">
        <table id="" class="table e_MAR_tb_prescription_upcoming">
            <thead>
                <tr>
                    <th>Drug</th>
                    <th>Med Time</th>
                    <th>Last Administered</th>
                    <th>Warning</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_query = "select med_logs.prescription_id,med_logs.id,med_logs.is_prn,med_logs.provider_id,med_logs.order_set, patient_data.fname, patient_data.lname, `date_added`, `drug`, 
										(SELECT title FROM `list_options` where list_id = 'drug_form'and option_id = `form` and activity = 1) as drug_form, 
										`dosage`,
										`size`, med_logs.note,med_logs.warning_txt,
										(SELECT title FROM `list_options` where list_id = 'drug_units'and option_id = `unit` and activity = 1) as drug_units, 
										(SELECT title FROM `list_options` where list_id = 'drug_route'and option_id = `route` and activity = 1) as drug_route, 
										(SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `interval` and activity = 1) as drug_interval, 
										`med_time`, `administered_by`, `did_administer`, `administered_note`, `patient_signed`, `patient_signed_time`,`start_date`,`interval` 
										from med_logs, patient_data where med_logs.active = 1 and `med_logs`.p_delete='" . $p_delete . "' AND  patient_data.pid=med_logs.patient_id and med_logs.med_time > NOW() + INTERVAL 2 HOUR and med_logs.did_administer = 0 and patient_data.pid='" . $pid . "' AND (med_logs.is_prn != 1 OR med_logs.is_prn IS NULL) AND `interval` != 17 AND `interval` != 18 AND CAST(med_logs.med_time as Date) = CAST(NOW() as Date) ORDER By  med_time ASC";
                // echo $sql_query; exit;
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
                            <?= $developer['dosage']; ?>-<?= $developer['drug_form']; ?>, <?= $developer['drug_route']; ?>, <?= $developer['drug_interval'] ?> - <strong style="font-size:13px;"><?= $developer['size']; ?> <?= $developer['drug_units']; ?></strong>

                            <?php if ($developer['interval'] != 18 && $developer['is_prn'] || $developer['interval'] == 17) : ?>
                                <p class="py-1" style="color: darkblue;"> (P.R.N) When Necessary</p>
                            <?php endif; ?>
                            <br>
                            <p class="mt-2"><span class="provider_name"><?= $providers[$developer['provider_id']]['fname']; ?> <?= $providers[$developer['provider_id']]['lname']; ?> </span>: <?php if ($developer['note']) : ?><span class="p-1" style="background:#F5EDDC; color: darkblue;word-wrap: anywhere"><?= $developer['note']; ?></span><?php endif; ?> </p>
                            <br>
                            <p><?= $name; ?></p>
                        </td>

                        <td><?= date("m-d-Y H:i:s", strtotime($developer['med_time'])); ?></td>
                        <?php
                        $prescription_id = $developer['prescription_id'];
                        $sql_la = "SELECT `update_time` FROM `med_logs` WHERE `prescription_id`='$prescription_id' AND `did_administer`='1' ORDER BY `updated_by` DESC LIMIT 1";
                        $res_la = sqlStatement($sql_la);
                        $row_la = sqlFetchArray($res_la);
                        ?>
                        <td style="color: green;"><?= $row_la['update_time']; ?> </td>

                        <td style="word-wrap: anywhere;"><?php echo $developer['warning_txt']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>