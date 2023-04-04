<div class="row" id="iRx_meds" style="display: none;">

    <div class="col-md-12 mt-3 mb-2">
        <h5><b>Medication </b></h5>
    </div>
    <div class="col-md-12 mb-4">
        <table id="" class="table e_MAR_tb_iRx">
            <thead>
                <tr>
                    <th>Drug</th>
                    <th>Start Date</th>
                    <!-- <th>Med Time</th> -->
                    <th>End Date</th>
                    <th>Meds Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_query = "SELECT prescriptions.id,prescriptions.drug,prescriptions.start_date,prescriptions.end_date,prescriptions.datetime,prescriptions.rxnorm_drugcode,prescriptions.drug_id,prescriptions.active,prescriptions.user,
                (SELECT title FROM `list_options` where list_id = 'drug_form'and option_id = `form` and activity = 1) as drug_form, `dosage`, `continue_on_discharge`,`size`, 
                (SELECT title FROM `list_options` where list_id = 'drug_units'and option_id = `unit` and activity = 1) as drug_units, 
                (SELECT title FROM `list_options` where list_id = 'drug_route'and option_id = `route` and activity = 1) as drug_route, 
                (SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `instruction` and activity = 1) as drug_instruction, 
                (SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `interval` and activity = 1) as drug_interval FROM `prescriptions` WHERE `prescriptions`.patient_id='" . $pid . "' AND `prescriptions`.erx_source='1' AND `prescriptions`.p_delete='" . $p_delete . "' AND `prescriptions`.prescriptionguid IS NOT NULL ORDER BY start_date ASC";
                try {
                    $res = sqlStatement($sql_query);
                    while ($developer = sqlFetchArray($res)) {
                        $drug_code = $developer['drug_id'];
                        $sql_query1 = "SELECT * FROM `viewwsdrug` WHERE `DrugID`='$drug_code'";
                        $res1 = sqlStatement($sql_query1);
                        $row = sqlFetchArray($res1);


                        if ($developer['active'] == 0) {
                            $aclass = 'inactive_med';
                        }
                ?>
                        <tr class="<?= $aclass ?>">
                            <td>
                                <h6 class=" drug_font"><?= $row['drug'] ?> </h6>
                                <?= $developer['dosage']; ?>-<?= $developer['drug_form']; ?>, <?= $developer['drug_route']; ?>, <?= $developer['drug_interval'] ?> - <strong style="font-size:13px;"><?= $developer['size']; ?> <?= $developer['drug_units']; ?></strong>
                                <br>
                                <span class="date_added_class"><?= date("m-d-Y H:i:s", strtotime($developer['datetime'])); ?></span>
                                <br>
                                <span>By : <?= $providers[$developer['user']]['fname']; ?> <?= $providers[$developer['user']]['lname']; ?> </span>
                            </td>
                            <td>
                                <?= date("m-d-Y", strtotime($developer['start_date'])); ?>
                            </td>

                            <!-- By Rudrayya For Medtime Dropdown start  -->
                            <!-- <td>

                                <div class="dropdown">
                                    <span><u>Show Med times</u></span>
                                    <div class="dropdown-content">
                                        <?php
                                        $med_time_arr = explode(',', $developer['med_time']);
                                        foreach ($med_time_arr as $med_time_data) {  ?>
                                            <p>
                                                <?= $med_time_data ?>
                                            </p>
                                        <?php } ?>
                                    </div>
                                </div>

                            </td> -->
                            <!-- By Rudrayya For Medtime Dropdown End  -->

                            <td>
                                <?= ($developer['end_date']) ? date("m-d-Y", strtotime($developer['end_date'])) : ""; ?>
                            </td>

                            <td>
                                <?php echo ($developer['active'] == 0) ? 'Discontinued medication' : 'Active'; ?>
                            </td>
                        </tr>
                <?php }
                } catch (exception $e) {
                }
                ?>
            </tbody>
        </table>
    </div>

    <hr>
    <div class="col-md-12 mb-2">
        <h5><b>Allergies</b></h5>
    </div>
    <div class="col-md-12 mb-4">
        <table id="" class="table e_MAR_tb_iRx">
            <thead>
                <tr>
                    <th>Allergies</th>
                    <th>Start Date</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_query = "SELECT * FROM `lists` WHERE `pid`='" . $pid . "' AND `type`='allergy' AND `erx_source`='1' ORDER BY id ASC";
                try {
                    $res = sqlStatement($sql_query);
                    while ($allergies = sqlFetchArray($res)) {
                ?>
                        <t>
                            <td>
                                <h6 class=" drug_font"><?= $allergies['title'] ?> </h6>
                            </td>
                            <td>
                                <?= date("m-d-Y", strtotime($allergies['begdate'])); ?>
                            </td>

                            <td>
                                <!-- <?= ($developer['end_date']) ? date("m-d-Y", strtotime($developer['end_date'])) : ""; ?> -->
                            </td>

                            <td>
                                <!-- <?php echo ($developer['active'] == 0) ? 'Discontinued medication' : 'Active'; ?> -->
                            </td>
                            </tr>
                    <?php }
                } catch (exception $e) {
                }
                    ?>
            </tbody>
        </table>
    </div>

    <hr>
    <div class="col-md-12 mb-2">
        <h5><b>Diagnosis Codes</b></h5>
    </div>
    <div class="col-md-12 mb-4">
        <table id="" class="table e_MAR_tb_iRx">
            <thead>
                <tr>
                    <th>Diagnosis Code</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_query = "SELECT * FROM `lists` WHERE `pid`='" . $pid . "' AND `type`='medical_problem' AND `erx_source`='1' ORDER BY id ASC";
                try {
                    $res = sqlStatement($sql_query);
                    while ($allergies = sqlFetchArray($res)) {
                ?>
                        <t>
                            <td>
                                <h6 class=" drug_font"><?= $allergies['diagnosis'] ?> </h6>
                            </td>
                            <td>
                                <h6 class=" drug_font"><?= $allergies['title'] ?> </h6>
                            </td>
                            <td>
                                <?= date("m-d-Y", strtotime($allergies['begdate'])); ?>
                            </td>

                            <td>
                                <!-- <?= ($developer['end_date']) ? date("m-d-Y", strtotime($developer['end_date'])) : ""; ?> -->
                            </td>
                            </tr>
                    <?php }
                } catch (exception $e) {
                }
                    ?>
            </tbody>
        </table>
    </div>

</div>