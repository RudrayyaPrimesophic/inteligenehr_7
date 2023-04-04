<div class="row" id="meds_broughtin" style="display: none;">
    <div class=" col-md-12 mb-4 text-right">
        <a href="javascript:;" class="btn-default  <?= $disable_add_btns ?>" data-toggle="modal" data-target="#add_meds">Add Meds Brought In</a>

    </div>
    <div class="col-md-12">
        <table id="" class="table e_MAR_tb">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>Medication</th>
                    <th>Quantity</th>
                    <th>Directions</th>
                    <th>Hold</th>
                    <th>Amount on Hand</th>
                    <th>Last taken</th>
                    <th>Prescribed by</th>
                    <th>Logged By</th>
                    <th>Approved by</th>
                    <th>Verbal</th>
                    <th>Amount Returned</th>
                    <th>Date & Time</th>
                    <th>Amount Destroyed</th>
                    <th>Continue on discharge</th>
                    <th>Witness</th>
                    <th>Warning Text</th>

                </tr>
            </thead>
            <tbody>
                <?php
                // $sql_query = "SELECT med.*, (SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = med.`frequency`) as drug_interval FROM `form_med_reconcilation_brought_in` med WHERE pid=". $pid ." AND med.id IN(SELECT med_brought_in_id FROM prescriptions where patient_id = ". $pid .")";
                // echo $sql_query;

                //Changes by Latha
                //$sql_query = "SELECT med.*, IFNULL(p.med_brought_in_id, 0) as med_brought_in_id, (SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = med.`frequency`) as drug_interval FROM `form_med_reconcilation_brought_in` med LEFT JOIN prescriptions p ON p.med_brought_in_id = med.id WHERE pid='". $pid."'";
                $sql_query = "SELECT fmrb.pid,fmrb.id,fmrb.hold,fmrb.amnt_on_hand,fmrb.last_taken,fmrb.prescribe,fmrb.logged_by,fmrb.broughtin_status,fmrb.drug_id,fmrb.warning_txt,fmrb.is_prn,u.fname,u.lname,fmrb.verbal,fmrb.amt_returned,fmrb.time,fmrb.amount_destroyed,fmrb.continue_discharge,fmrb.witness,fmrb.encounter,
                        (SELECT title FROM `list_options` where list_id = 'drug_form'and option_id = `form` and activity = 1) as form, `medication`,
                        `dosage`, `size`, (SELECT title FROM `list_options` where list_id = 'drug_units'and option_id = `unit` and activity = 1) as unit, 
                        (SELECT title FROM `list_options` where list_id = 'drug_route'and option_id = `route` and activity = 1) as route, 
                        (SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `frequency`and activity = 1) as frequency
                        FROM `form_med_reconcilation_brought_in` fmrb left join users u on u.id = fmrb.approved_by WHERE fmrb.p_delete='" . $p_delete . "' AND fmrb.pid='" . $pid . "'";
                // end
                $res = sqlStatement($sql_query);
                $i = 0;
                $id = 0;

                while ($row = sqlFetchArray($res)) {
                    $id = $row['id'];
                    if ($row['frequency'] == 18) {
                        $class = 'stat_does_med';
                    } else if ($row['frequency'] == 17 || $row['is_prn'] == 1) {
                        $class = 'PRN_med';
                    } else {
                        $class = "";
                    }

                    if ($row['frequency'] == 17 || $row['is_prn'] == 1) {
                        $txt = "(P.R.N When Necessary)";
                    } else {
                        $txt = "";
                    }

                ?>
                    <tr id="<?php echo $row['id']; ?>" class="<?= $class ?>" <?php if ($row['hold'] == '1') echo "style='background-color:#cccaca'";  ?>>
                        <td>
                            <?php if ($access_to_continue_meds_btn && !$row['broughtin_status'] && !$row['hold']) { ?>
                                <button name="continue_meds" id="<?= $row['id'] ?>" class="btn btn-primary continue_meds btn-sm" <?= ($row['med_brought_in_id'] != 0) ? "disabled" : ""; ?>>
                                    <span><?php echo xlt('Continue on Stay'); ?></span>
                                </button>
                            <?php   } ?>
                        </td>
                        <td><button class="btn update btn-primary btn-sm" onclick="editMedsBrought(<?php echo $id; ?>)" data-sfid='"<?php echo $id; ?>"'>Edit</button></td>

                        <td>
                            <p class="drug_font"><b><?php echo $row['medication']; ?></b></p>
                        </td>
                        <td> <?php echo $row['dosage'] ?></td>

                        <td><?= $row['form']; ?>, <?= $row['route']; ?>, <?= $row['frequency'] ?> - <strong style="font-size:13px;"><?= $row['size']; ?> <?= $row['unit']; ?></strong> <?= $txt ?></td>
                        <td style="text-align: center;"><input type="hidden" class="hid_hold" name="hold" value="0">
                            <input class="form-check-input hid_hold" id="hold" type="checkbox" name="hold" value="1" <?php if ($row['hold'] == '1') echo 'checked'; ?> disabled="disabled">
                        </td>

                        <td><?php echo $row['amnt_on_hand']; ?></td>

                        <td><?php echo ($row['last_taken'] != "") ? $row['last_taken'] : ''; ?></td>

                        <td><?php echo $row['prescribe']; ?></td>
                        <td><?php echo $row['logged_by']; ?></td>
                        <td>
                            <?php echo $row['fname'] . ' ' . $row['lname']; ?>
                        </td>
                        <td><?php echo $row['verbal']; ?></td>
                        <td><?php echo $row['amt_returned']; ?></td>
                        <td><?php echo ($row['time']) ? $row['time'] : ''; ?></td>
                        <td><?php echo $row['amount_destroyed']; ?></td>
                        <td style="text-align: center;"><input type="hidden" class="hid_continue_discharge" name="continue_discharge" value="0">
                            <input class="form-check-input hid_continue_discharge " id="continue_discharge" type="checkbox" name="continue_discharge" value="1" <?php if ($row['continue_discharge'] == '1') echo 'checked'; ?> disabled="disabled">
                        </td>
                        <td><?php echo $row['witness']; ?></td>
                        <td><?php echo $row['warning_txt']; ?></td>

                    </tr>
                <?php  } ?>
            </tbody>
        </table>
    </div>
</div>