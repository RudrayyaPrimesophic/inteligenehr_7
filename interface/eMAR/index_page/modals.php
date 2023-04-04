<!-- Add Vitals index Modal -->
<?php
// require_once("../../globals.php");
require_once(dirname(__FILE__) . "/../../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/encounter.inc");

use OpenEMR\Common\Csrf\CsrfUtils;

?>
<div class="modal preview-modal" id="add_vitals_index" data-backdrop="" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Vitals</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: black;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action=" " id="vital_form" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="pid" value="<?= $pid ?>">
                        <input type="hidden" name="add_vitals_data" value="yes">
                        <div class="col-md-6">
                            <p class="p-tag">Date and time of observation</p>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control datepicker" name="date_time" id="date_time" value="">
                        </div>
                        <div class="col-md-6">
                            <p class="p-tag">Weight[lbs]</p>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="weight" id="weight" value="">
                        </div>
                        <div class="col-md-6">
                            <p class="p-tag">Height</p>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="height" id="height" value="">
                        </div>
                        <div class="col-md-6">
                            <p class="p-tag">BP Systolic</p>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control bps" name="bps" id="bps" value="">
                        </div>
                        <div class="col-md-6">
                            <p class="p-tag">BP Diastolic</p>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control bpd" name="bpd" id="bpd" value="">
                        </div>
                        <div class="col-md-6">
                            <p class="p-tag">Pulse</p>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control pulse" name="pulse" id="pulse" value="">
                        </div>
                        <div class="col-md-6">
                            <p class="p-tag">Temperature</p>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control temperature" name="temperature" id="temperature" value="">
                        </div>
                        <div class="col-md-6">
                            <p class="p-tag">Respiration</p>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control respiration" name="respiration" id="respiration" value="">
                        </div>
                        <div class="col-md-6">
                            <p class="p-tag">O2 Saturation</p>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control oxygen_saturation" name="oxygen_saturation" id="oxygen_saturation" value="">
                        </div>

                        <div class="col-md-6">
                            <p class="p-tag">BMI</p>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="BMI" id="BMI" value="">
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" id="submit_vitals" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Doctors Order Modal -->
<div class="modal preview-modal" id="add_vital_check" data-backdrop="" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Doctors Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: black;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= $webroot ?>/interface/eMAR/index.php" method="post" id="doctors_orders_form">
                <div class="modal-body">
                    <?php $uid = $_SESSION['authUserID']; ?>
                    <?php $approved_flora = $GLOBALS['approved_by']; ?>
                    <div class="row">
                        <input type="hidden" name="pid" value="<?= $pid ?>">
                        <input type="hidden" name="add_check_vital" value="yes">
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Start Date</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="" class="form-control datepicker" name="start_date" value="" autocomplete="off">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">End date</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control datepicker12" name="end_date" value="" autocomplete="off">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Order</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <select class="form-control" name="vital_check" id='doctor_vital_check'>
                                <option value="Vital Check">Vital Check</option>
                                <option value="Check COWS">Check COWS</option>
                                <option value="Check CIWA Ar">Check CIWA Ar</option>
                                <option value="Check CIWA B">Check CIWA B</option>
                                <option value="Order CPAP machine">Order CPAP machine</option>
                                <option value="One to one supervision">One to one supervision</option>
                                <option value="Naloxone teaching to be done">Naloxone teaching to be done</option>
                                <option vlaue="Narcan kit upon Discharge">Narcan kit upon Discharge</option>
                                <option value="Admit to ATS">Admit to ATS</option>
                                <option value="Admit to CSS">Admit to CSS</option>
                                <option value="other">Other</option>
                            </select>
                            <input class="form-control mt-2" value="" placeholder="Other Order" id='doctor_vital_check_other' name='doctor_vital_check_other' style="display: none;" />

                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Frequency</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <select class="form-control" name="frequency">
                                <option value=""></option>
                                <option value="15 minutes">15 minutes</option>
                                <option value="30 minutes">30 minutes</option>
                                <option value="1 hour">1 hour</option>
                                <option value="2 hour">2 hour</option>
                                <option value="4 hour">4 hour</option>
                                <option value="6 hour">6 hour</option>
                                <option value="8 hour">8 hour</option>
                                <option value="12 hour">12 hour</option>
                                <option value="24 hour">24 hour</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Note:</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <textarea type="text" class="form-control" name="note"></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div style='<?php if (array_key_exists("$uid", $approved_flora)) {
                                            echo ("display:none");
                                        } ?>'>
                                <p class="p-tag">Provider Name</p>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div style='<?php if (array_key_exists("$uid", $approved_flora)) {
                                            echo ("display:none");
                                        } ?>'>
                                <select name="provider_id" class="form-control" <?php if (!array_key_exists("$uid", $approved_flora)) {
                                                                                    echo ("required");
                                                                                } ?>>
                                    <option value="">Please Select Provider</option>
                                    <?php $approved_flora = $GLOBALS['approved_by']; ?>
                                    <?php
                                    foreach ($approved_flora as $key => $value) { ?>
                                        <option value="<?php echo $key ?>" <?php if ($key == $_SESSION['authUserID']) {
                                                                                echo ("selected");
                                                                            } ?>><?php echo $value ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div style='<?php if (array_key_exists("$uid", $approved_flora)) {
                                            echo ("display:none");
                                        } ?>'>
                                <p class="p-tag">Verbal</p>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div style='<?php if (array_key_exists("$uid", $approved_flora)) {
                                            echo ("display:none");
                                        } ?>'>
                                <select name="verbal_order" class="form-control" <?php if (!array_key_exists("$uid", $approved_flora)) {
                                                                                        echo ("required");
                                                                                    } ?>>
                                    <option value="" <?php if (array_key_exists("$uid", $approved_flora)) {
                                                            echo ("selected");
                                                        } ?>>Please Select Verbal</option>
                                    <option value="by_phone">By Phone</option>
                                    <option value="in_person">In Person</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Disable Medication Modal -->
<div class="modal preview-modal" id="disable_medication" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Discontinue medication</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: black;"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <?php $uid = $_SESSION['authUserID']; ?>
                <div class="row mb-3">
                    <div class="col-4">
                        <span>Reason</span>
                    </div>

                    <div class="col-4">
                        <div style='<?php if (array_key_exists("$uid", $approved_flora)) {
                                        echo ("display:none");
                                    } ?>'>
                            <span>Provider Name</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div style='<?php if (array_key_exists("$uid", $approved_flora)) {
                                        echo ("display:none");
                                    } ?>'>
                            <span>Verbal Order</span>
                        </div>
                    </div>

                    <div class="col-4">
                        <input type="text" id="discontinuation_reason" class="form-control">
                    </div>

                    <div class="col-4">
                        <div style='<?php if (array_key_exists("$uid", $approved_flora)) {
                                        echo ("display:none");
                                    } ?>'>
                            <select id="provider_id" class="form-control" <?php if (!array_key_exists("$uid", $approved_flora)) {
                                                                                echo ("required");
                                                                            } ?>>
                                <option value="0">Please Select Provider</option>
                                <?php $approved_flora = $GLOBALS['approved_by']; ?>
                                <?php
                                foreach ($approved_flora as $key => $value) { ?>
                                    <option value="<?php echo $key ?>" <?php if ($key == $_SESSION['authUserID']) {
                                                                            echo ("selected='selected'");
                                                                        } ?>><?php echo $value ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div style='<?php if (array_key_exists("$uid", $approved_flora)) {
                                        echo ("display:none");
                                    } ?>'>
                            <select id="verbal" class="form-control" <?php if (!array_key_exists("$uid", $approved_flora)) {
                                                                            echo ("required");
                                                                        } ?>>
                                <option value="0">Please Select Verbal</option>
                                <option value="by_phone">By Phone</option>
                                <option value="in_person" <?php if (array_key_exists("$uid", $approved_flora)) {
                                                                echo ("selected='selected'");
                                                            } ?>>In Person</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div id="accordion">
                    <?php
                    $sql_qry = "SELECT `prescriptions`.`order_set`,`orderset`.`name` FROM `prescriptions` JOIN `orderset` ON `prescriptions`.`order_set`=`orderset`.`id` WHERE `prescriptions`.`patient_id`='" . $pid . "' AND `prescriptions`.`encounter` = '" . $encounter . "' AND `prescriptions`.`active` = '1'  AND (`prescriptions`.`order_set` NOT IN ('0')) GROUP BY `prescriptions`.`order_set`";
                    $res = sqlStatement($sql_qry);
                    while ($order_set = sqlFetchArray($res)) {

                    ?>
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <input type="checkbox" name="" class="disable_order_set" data-order_set_id="<?= $order_set['order_set'] ?>">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#<?= $order_set['order_set'] ?>" aria-expanded="true" aria-controls="collapseOne">
                                        <?= $order_set['name'] ?>
                                    </button>
                                </h5>
                            </div>

                            <div id="<?= $order_set['order_set'] ?>" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="row">
                                        <?php
                                        $sql_qry2 = "SELECT prescriptions.id,prescriptions.is_prn,prescriptions.provider_id,prescriptions.order_set,prescriptions.active,prescriptions.order_set,prescriptions.`date_added`,prescriptions.`drug`,prescriptions.`quantity`,prescriptions.`med_brought_in_id`,prescriptions.`date_modified`,prescriptions.`updated_by`,prescriptions.`cancelled_provider`,prescriptions.`cancelled_reason`,prescriptions.`cancelled_verbal`,
                                            (SELECT title FROM `list_options` where list_id = 'drug_form'and option_id = `form` and activity = 1) as drug_form, `dosage`, `continue_on_discharge`, `size`, 
                                            (SELECT title FROM `list_options` where list_id = 'drug_units'and option_id = `unit` and activity = 1) as drug_units, 
                                            (SELECT title FROM `list_options` where list_id = 'drug_route'and option_id = `route` and activity = 1) as drug_route, 
                                            (SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `instruction` and activity = 1) as drug_instruction, 
                                            (SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `interval` and activity = 1) as drug_interval, 
                                            `datetime`, `start_date` , `note`,
                                            (SELECT max(med_time) FROM `med_logs` WHERE prescription_id = prescriptions.`id`) as enddate, `interval`,
                                            (SELECT max(end_date) FROM `med_logs` WHERE prescription_id = prescriptions.`id`) as enddate2,med_time,(SELECT DISTINCT GROUP_CONCAT(med_time, ', ') FROM `med_logs` WHERE prescription_id = prescriptions.`id` GROUP BY prescription_id) as medtime2 FROM prescriptions WHERE prescriptions.encounter = '" . $encounter . "' AND prescriptions.patient_id='$pid' AND prescriptions.order_set='" . $order_set['order_set'] . "' and `prescriptions`.p_delete='0'  AND prescriptions.active='1' ";
                                        $res2 = sqlStatement($sql_qry2);

                                        while ($drug_data = sqlFetchArray($res2)) {
                                            if ($drug_data['is_prn'] || $drug_data['interval'] == 17) {
                                                $class = 'PRN_med';
                                            } else if ($drug_data['interval'] == 18) {
                                                $class = 'stat_does_med';
                                            } else if ($drug_data['order_set'] == 1) {
                                                $class = "from_orderset_med";
                                            } else {
                                                $class = "";
                                            }

                                        ?>
                                            <div class="col-12 border-bottom mb-2 <?= $class ?>">
                                                <div class="row">
                                                    <div class="col-1">
                                                        <input type="checkbox" name="" class="disable_meds" data-id="<?= $drug_data['id'] ?>">
                                                    </div>
                                                    <div class="col-7">
                                                        <h6><?= $drug_data['drug'] ?></h6>
                                                        <?= $drug_data['dosage']; ?>-<?= $drug_data['drug_form']; ?>, <?= $drug_data['drug_route']; ?>, <?= $drug_data['drug_interval'] ?> - <strong style="font-size:13px;"><?= $drug_data['size']; ?> <?= $drug_data['drug_units']; ?></strong>
                                                        <br>
                                                        <?php if ($drug_data['interval'] != 18 && $drug_data['is_prn'] || $drug_data['interval'] == 17) : ?>
                                                            <p class=" py-1" style="color:darkblue;"> (P.R.N) When Necessary</p>
                                                        <?php endif; ?>
                                                        <p class="mt-2"><span class="provider_name"><?= $providers[$drug_data['provider_id']]['fname']; ?> <?= $providers[$drug_data['provider_id']]['lname']; ?> </span> : <?php if ($drug_data['note']) : ?><span class="p-1" style="background:#F5EDDC; color: darkblue;font-size: 13px;word-wrap: anywhere"><?= $drug_data['note']; ?></span><?php endif; ?> </p>
                                                        <br>
                                                        <span class="date_added_class"><?= date("m-d-Y H:i:s", strtotime($drug_data['date_added'])); ?></span>
                                                    </div>
                                                    <div class="col-2">
                                                        <p><?= date("m-d-Y", strtotime($drug_data['start_date'])); ?></p>
                                                    </div>
                                                    <div class="col-2">
                                                        <p><?= ($drug_data['enddate2']) ? date("m-d-Y", strtotime($drug_data['enddate2'])) : date("m-d-Y", strtotime($drug_data['enddate'])); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <input type="checkbox" name="" class="disable_other_meds">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#other_meds" aria-expanded="true" aria-controls="collapseOne">
                                    Others Medication
                                </button>
                            </h5>
                        </div>

                        <div id="other_meds" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <?php
                                    $sql_qry3 = "SELECT prescriptions.id,prescriptions.is_prn,prescriptions.provider_id,prescriptions.order_set,prescriptions.active,prescriptions.order_set,prescriptions.`date_added`,prescriptions.`drug`,prescriptions.`quantity`,prescriptions.`med_brought_in_id`,prescriptions.`date_modified`,prescriptions.`updated_by`,prescriptions.`cancelled_provider`,prescriptions.`cancelled_reason`,prescriptions.`cancelled_verbal`,
                                            (SELECT title FROM `list_options` where list_id = 'drug_form'and option_id = `form` and activity = 1) as drug_form, `dosage`, `continue_on_discharge`, `size`, 
                                            (SELECT title FROM `list_options` where list_id = 'drug_units'and option_id = `unit` and activity = 1) as drug_units, 
                                            (SELECT title FROM `list_options` where list_id = 'drug_route'and option_id = `route` and activity = 1) as drug_route, 
                                            (SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `instruction` and activity = 1) as drug_instruction, 
                                            (SELECT title FROM `list_options` where list_id = 'drug_interval'and option_id = `interval` and activity = 1) as drug_interval, 
                                            `datetime`, `start_date` , `note`,
                                            (SELECT max(med_time) FROM `med_logs` WHERE prescription_id = prescriptions.`id`) as enddate, `interval`,
                                            (SELECT max(end_date) FROM `med_logs` WHERE prescription_id = prescriptions.`id`) as enddate2,med_time,(SELECT DISTINCT GROUP_CONCAT(med_time, ', ') FROM `med_logs` WHERE prescription_id = prescriptions.`id` GROUP BY prescription_id) as medtime2 FROM prescriptions WHERE prescriptions.encounter = '" . $encounter . "' AND prescriptions.patient_id='$pid' AND prescriptions.order_set='0' AND prescriptions.active='1' ";
                                    $res3 = sqlStatement($sql_qry3);

                                    while ($drug_data2 = sqlFetchArray($res3)) {
                                        if ($drug_data2['interval'] == 18) {
                                            $class = 'stat_does_med';
                                        } else if ($drug_data2['is_prn'] || $drug_data2['interval'] == 17) {
                                            $class = 'PRN_med';
                                        } else if ($drug_data2['order_set'] == 1) {
                                            $class = "from_orderset_med";
                                        } else {
                                            $class = "";
                                        }

                                    ?>
                                        <div class="col-12 border-bottom mb-2 <?= $class ?>">
                                            <div class="row">
                                                <div class="col-1">
                                                    <input type="checkbox" name="" class="disable_meds" data-id="<?= $drug_data2['id'] ?>">
                                                </div>
                                                <div class="col-7">
                                                    <h6><?= $drug_data2['drug'] ?></h6>
                                                    <?= $drug_data2['dosage']; ?>-<?= $drug_data2['drug_form']; ?>, <?= $drug_data2['drug_route']; ?>, <?= $drug_data2['drug_interval'] ?> - <strong style="font-size:13px;"><?= $drug_data2['size']; ?> <?= $drug_data2['drug_units']; ?></strong>
                                                    <br>
                                                    <?php if ($drug_data2['interval'] != 18 && $drug_data2['is_prn'] || $drug_data2['interval'] == 17) : ?>
                                                        <p class=" py-1" style="color:darkblue;"> (P.R.N) When Necessary</p>
                                                    <?php endif; ?>
                                                    <p class="mt-2"><span class="provider_name"><?= $providers[$drug_data2['provider_id']]['fname']; ?> <?= $providers[$drug_data2['provider_id']]['lname']; ?> </span> : <?php if ($drug_data2['note']) : ?><span class="p-1" style="background:#F5EDDC; color: darkblue;font-size: 13px;word-wrap: anywhere"><?= $drug_data2['note']; ?></span><?php endif; ?> </p>
                                                    <br>
                                                    <span class="date_added_class"><?= date("m-d-Y H:i:s", strtotime($drug_data2['date_added'])); ?></span>
                                                </div>
                                                <div class="col-2">
                                                    <p><?= date("m-d-Y", strtotime($drug_data2['start_date'])); ?></p>
                                                </div>
                                                <div class="col-2">
                                                    <p><?= ($drug_data2['enddate2']) ? date("m-d-Y", strtotime($drug_data2['enddate2'])) : date("m-d-Y", strtotime($drug_data2['enddate'])); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary disable_medication_btn">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Allergies Modal -->
<div class="modal preview-modal" id="add_allergies_box" data-backdrop="" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Allergies</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: black;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= $webroot ?>/interface/eMAR/index.php" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="pid" value="<?= $pid ?>">
                        <input type="hidden" name="add_allergy" value="yes">
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Allergy type</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <select class="form-control" name="allergy_type">
                                <option value="Drug">Drug</option>
                                <option value="Environment">Environment</option>
                                <option value="Food">Food</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Allergen</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="allergen" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Reaction type</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <select class="form-control" name="reaction_type">
                                <option value="Adverse reaction">Adverse reaction</option>
                                <option value="Allergy">Allergy</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Reaction</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="reaction" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Begin date</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control datepicker" name="begin_date" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Treatment</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="treatment" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Status</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <select class="form-control" name="status_code">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Source of reporting</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <select class="form-control" name="source_of_report" selected="">
                                <option value="Self">Self</option>
                                <option value="Provided documentation">Provided documentation
                                </option>
                                <option value="Tested/Verified">Tested/Verified</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Meds Modal -->
<div class="modal preview-modal" id="add_meds" data-backdrop="" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Meds Brought In</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: black;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php //echo  $webroot."/interface/eMAR/index.php"; 
            ?>
            <form action="" method="POST" id="add_medsbrought_form_name">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="pid" value="<?= $pid ?>">
                        <input type="hidden" name="add_meds_broughtin" value="yes">
                        <input type="hidden" name="encounter" value="<?= $encounter ?>">
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Medication</p>
                        </div>
                        <div class="col-md-6 mb-3 position-relative">
                            <input type="hidden" class="new_medication" id="broughtin_drug_id" name="drug_id">
                            <input type="text" class="form-control broughtin_medication" name="medication" value="">
                            <div class="position-absolute zindex-fixed hideme medication_name_list " style="z-index:1500;">
                                <ul class="drugs_list_medication list-group" style="height: 300px; overflow-y:scroll;"></ul>
                            </div>
                        </div>


                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Hold</p>
                        </div>
                        <div class="col-md-6 mb-3"><input type="hidden" class="hid_hold" name="hold" value="0">
                            <input class="form-check-input hid_hold" id="hold" type="checkbox" name="hold" value="1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Total Dose</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <input type="text" class="form-control" name="size" value="">
                        </div>
                        <div class="col-md-3 mb-3">
                            <select name="unit" class="form-control">
                                <option value=""></option>
                                <?php
                                $sql_query = "SELECT title,option_id FROM `list_options` where list_id = 'drug_units' and activity = '1'";
                                $res = sqlStatement($sql_query);
                                while ($drug_units = sqlFetchArray($res)) {
                                ?>
                                    <option value="<?php echo $drug_units['option_id'] ?>"><?php echo $drug_units['title'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3 g-0">
                            <p class="p-tag">Directions</p>
                        </div>

                        <div class="col-md-2 mb-3 g-0">
                            <input type="text" class="form-control" name="dosage" value="">
                        </div>

                        <div class="col-md-2 mb-3 g-0">
                            <select name="form" class="form-control">
                                <option value=""></option>
                                <?php
                                $sql_query = "SELECT title,option_id FROM `list_options` where list_id = 'drug_form' and activity = '1'";
                                $res = sqlStatement($sql_query);
                                while ($drug_form = sqlFetchArray($res)) {
                                ?>
                                    <option value="<?php echo $drug_form['option_id'] ?>"><?php echo $drug_form['title'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-2 mb-3 g-0">
                            <select name="route" class="form-control">
                                <option value=""></option>
                                <?php
                                $sql_query = "SELECT title,option_id FROM `list_options` where list_id = 'drug_route' and activity = '1'";
                                $res = sqlStatement($sql_query);
                                while ($drug_route = sqlFetchArray($res)) {
                                ?>
                                    <option value="<?php echo $drug_route['option_id'] ?>"><?php echo $drug_route['title'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-2 mb-3 g-0">
                            <select name="frequency" class="form-control interval">
                                <option value=""></option>
                                <?php
                                $sql_query = "SELECT title,option_id FROM `list_options` where list_id = 'drug_interval' and activity = '1'";
                                $res = sqlStatement($sql_query);
                                while ($frequency_result = sqlFetchArray($res)) {
                                ?>
                                    <option value="<?php echo $frequency_result['option_id'] ?>"><?php echo $frequency_result['title'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-2 mb-3 g-0">
                            <p class="p-tag">
                                <input type="checkbox" name="is_prn" id="" value='1'> Is Prn
                            </p>
                        </div>

                        <!-- <input type="text" class="form-control" name="frequency" value="" > -->

                        <div class="col-md-12 mt-1 mb-3">
                            <div class="row mb-3">
                                <div class="col-md-2" id="space_adjust"></div>
                                <div class="col-md-1 text-center px-0">
                                    <label class='med_time_label med_time_1' for="med_time_1">Time 1</label>
                                    <input size="5" type="text" class='  med_time med_time_1' id='med_time_1' name='med_time[]' />
                                </div>
                                <div class="col-md-1 text-center px-0">
                                    <label class='med_time_label med_time_2' for="med_time_2">Time 2</label>
                                    <input size="5" type="text" class='  med_time med_time_2' id='med_time_2' name='med_time[]' />
                                </div>
                                <div class="col-md-1 text-center px-0">
                                    <label class='med_time_label med_time_3' for="med_time_3">Time 3</label>
                                    <input size="5" type="text" class='  med_time med_time_3' id='med_time_3' name='med_time[]' />
                                </div>
                                <div class="col-md-1 text-center px-0">
                                    <label class='med_time_label med_time_4' for="med_time_4">Time 4</label>
                                    <input size="5" type="text" class=' med_time med_time_4' id='med_time_4' name='med_time[]' />
                                </div>
                                <div class="col-md-1 text-center px-0">
                                    <label class='med_time_label med_time_5' for="med_time_5">Time 5</label>
                                    <input size="5" type="text" class=' med_time med_time_5' id='med_time_5' name='med_time[]' />
                                </div>
                                <div class="col-md-1 text-center px-0">
                                    <label class='med_time_label med_time_6' for="med_time_6">Time 6</label>
                                    <input size="5" type="text" class='  med_time med_time_6' id='med_time_6' name='med_time[]' />
                                </div>
                                <div class="col-md-1 text-center px-0">
                                    <label class='med_time_label med_time_7' for="med_time_7">Time 7</label>
                                    <input size="5" type="text" class='  med_time med_time_7' id='med_time_7' name='med_time[]' />
                                </div>
                                <div class="col-md-1 text-center px-0">
                                    <label class='med_time_label med_time_8' for="med_time_8">Time 8</label></br>
                                    <input size="5" type="text" class='  med_time med_time_8' id='med_time_8' name='med_time[]' />
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Amount On Hand</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="number" class="form-control" name="amnt_on_hand" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Last Taken</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" name="last_taken" value="" class="form-control datepicker1" autocomplete="off">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Prescribed by</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="prescribe" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Logged by</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="logged_by" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <div style='<?php if (array_key_exists("$uid", $approved_flora)) {
                                            echo ("display:none");
                                        } ?>'>
                                <p class="p-tag">Approved by</p>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div style='<?php if (array_key_exists("$uid", $approved_flora)) {
                                            echo ("display:none");
                                        } ?>'>
                                <select name="approved_by" id="approved_by" class="form-control" <?php if (!array_key_exists("$uid", $approved_flora)) {
                                                                                                        echo ("required");
                                                                                                    } ?>>
                                    <option value="0">Please Select Provider</option>
                                    <?php $approved_flora = $GLOBALS['approved_by']; ?>
                                    <?php
                                    foreach ($approved_flora as $key => $value) { ?>
                                        <option value="<?php echo $key ?>" <?php if ($key == $_SESSION['authUserID']) {
                                                                                echo ("selected='selected'");
                                                                            } ?>><?php echo $value ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div style='<?php if (array_key_exists("$uid", $approved_flora)) {
                                            echo ("display:none");
                                        } ?>'>
                                <p class="p-tag">Verbal</p>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div style='<?php if (array_key_exists("$uid", $approved_flora)) {
                                            echo ("display:none");
                                        } ?>'>
                                <select id="verbal" name="verbal" class="form-control" <?php if (!array_key_exists("$uid", $approved_flora)) {
                                                                                            echo ("required");
                                                                                        } ?>>
                                    <option value="0">Please Select Verbal</option>
                                    <option value="by_phone">By Phone</option>
                                    <option value="in_person" <?php if (array_key_exists("$uid", $approved_flora)) {
                                                                    echo ("selected='selected'");
                                                                } ?>>In Person</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Amount Returned</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="number" class="form-control" name="amt_returned" id="amt_returned" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Date and Time</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" name="time" id="time" class="form-control datepicker1" value="" autocomplete="off">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Amount Destroyed</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="number" name="amount_destroyed" class="form-control" id="amount_destroyed" class="form-control" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Continue on discharge</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="hidden" class="hid_continue_discharge" name="continue_discharge" value="0">
                            <input class="form-check-input hid_continue_discharge" id="continue_discharge" type="checkbox" name="continue_discharge" value="1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Witness</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="witness" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="p-tag">Warning Text</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <textarea type="text" class="form-control" name="warning_txt"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="add_medsbrought_form" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Disable Doctors order modal -->
<div class="modal preview-modal" id="discontinue_doc_orders_modal" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Discontinue Doctors Orders</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: black;"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <?php $uid = $_SESSION['authUserID']; ?>
                <div class="row mb-3">
                    <div class="col-4">
                        <span>Reason</span>
                    </div>

                    <div class="col-4">
                        <div style='<?php if (array_key_exists("$uid", $approved_flora)) {
                                        echo ("display:none");
                                    } ?>'>
                            <span>Provider Name</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div style='<?php if (array_key_exists("$uid", $approved_flora)) {
                                        echo ("display:none");
                                    } ?>'>
                            <span>Verbal Order</span>
                        </div>
                    </div>

                    <div class="col-4">
                        <input type="text" id="doc_order_discontinuation_reason" class="form-control">
                    </div>

                    <div class="col-4">
                        <div style='<?php if (array_key_exists("$uid", $approved_flora)) {
                                        echo ("display:none");
                                    } ?>'>
                            <select id="doc_order_provider_id" class="form-control" <?php if (!array_key_exists("$uid", $approved_flora)) {
                                                                                        echo ("required");
                                                                                    } ?>>
                                <option value="0">Please Select Provider</option>
                                <?php $approved_flora = $GLOBALS['approved_by']; ?>
                                <?php
                                foreach ($approved_flora as $key => $value) { ?>
                                    <option value="<?php echo $key ?>" <?php if ($key == $_SESSION['authUserID']) {
                                                                            echo ("selected='selected'");
                                                                        } ?>><?php echo $value ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-4">
                        <div style='<?php if (array_key_exists("$uid", $approved_flora)) {
                                        echo ("display:none");
                                    } ?>'>
                            <select id="doc_order_verbal" class="form-control" <?php if (!array_key_exists("$uid", $approved_flora)) {
                                                                                    echo ("required");
                                                                                } ?>>
                                <option value="0">Please Select Verbal</option>
                                <option value="by_phone">By Phone</option>
                                <option value="in_person" <?php if (array_key_exists("$uid", $approved_flora)) {
                                                                echo ("selected='selected'");
                                                            } ?>>In Person</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary discontinue_doc_orders_final_btn">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {


        $('#submit_vitals').on('click', function() {
            console.log('transfering');
            let serverUrl = "/interface/eMAR/index_page/add_vitals.php?csrf_token_form=" + <?php echo js_url(CsrfUtils::collectCsrfToken()); ?>;
            console.log(serverUrl);
            $("#submit_vitals").attr("disabled", true);
            $('#add_vitals_index').modal('hide');

            $.ajax({
                url: serverUrl,
                type: "POST",
                data: {
                    "add_vitals_data": "true",
                    'date': $('#date_time').val(),
                    'weight': $('#weight').val(),
                    'height': $('#height ').val(),
                    'bps': $('#bps').val(),
                    'bpd': $('#bpd').val(),
                    'pulse': $('#pulse').val(),
                    'temperature': $('#temperature').val(),
                    'respiration': $('#respiration').val(),
                    'saturation': $('#oxygen_saturation').val(),
                    'bmi': $('#BMI').val()
                },
                success: function(response) {
                    console.log(response);
                    window.location.reload();
                },

            });
        });




    });
</script>