<?php
include("header.php");
$pid = $GLOBALS['pid'];
$id = $_GET['id'];
$url_webroot = $GLOBALS['webroot'];
$encounter = $_SESSION['encounter'];

if (isset($_REQUEST['edit_meds_broughtin'])) {

    $id = $_REQUEST['id'];
    $date = date("Y-m-d H:i:s");
    $pid = $_REQUEST['pid'];
    $medication = $_REQUEST['medication'];
    $dosage = $_REQUEST['dosage'];
    $hold = $_REQUEST['hold'];
    $frequency = $_REQUEST['frequency'];
    if (isset($_POST['is_prn'])) {
        $is_prn = 1;
    } else {
        $is_prn = 0;
    }
    $amnt_on_hand = $_REQUEST['amnt_on_hand'];
    $last_taken = '';
    if (isset($_REQUEST['last_taken']) && !empty($_REQUEST['last_taken'])) {
        $last_taken = $_REQUEST['last_taken'];
    }
    $prescribe = $_REQUEST['prescribe'];
    $logged_by = $_REQUEST['logged_by'];
    $approved_by = $_REQUEST['approved_by'];
    $verbal = $_REQUEST['verbal'];
    $amt_returned = $_REQUEST['amt_returned'];
    $continue_discharge = $_REQUEST['continue_discharge'];
    $amount_destroyed = $_REQUEST['amount_destroyed'];
    $time = '';
    if (isset($_REQUEST['time']) && !empty($_REQUEST['time'])) {
        $time = $_REQUEST['time'];
    }
    $witness = $_REQUEST['witness'];
    $size = $_REQUEST['size'];
    $unit = $_REQUEST['unit'];
    $route = $_REQUEST['route'];
    $form = $_REQUEST['form'];
    $warning_txt = $_REQUEST['warning_txt'];
    $med_time = implode(',', array_filter($_REQUEST['med_time']));
    $drug_id = $_REQUEST['drug_id'];



    $qry = "update form_med_reconcilation_brought_in set medication  = '" . $medication . "',dosage  = '" . $dosage . "',frequency  = '" . $frequency . "',is_prn  = '" . $is_prn . "',amnt_on_hand  = '" . $amnt_on_hand . "',last_taken  = '" . $last_taken . "',prescribe  = '" . $prescribe . "',logged_by  = '" . $logged_by . "',approved_by  = '" . $approved_by . "',verbal  = '" . $verbal . "', amt_returned  = '" . $amt_returned . "',time  = '" . $time . "',amount_destroyed  = '" . $amount_destroyed . "', continue_discharge  = '" . $continue_discharge . "',hold  = '" . $hold . "',witness  = '" . $witness . "',size  = '" . $size . "',unit  = '" . $unit . "',route  = '" . $route . "',form  = '" . $form . "',warning_txt  = '" . $warning_txt . "',med_time='" . $med_time . "',drug_id='" . $drug_id . "',encounter= '" . $_SESSION['encounter'] . "'
    where
    pid = '" . $pid . "' and id = '" . $id . "'";


    if ($res = sqlStatement($qry)) {
        log_user_event('eMAR - Edit Meds Broughtin popup', 'Updated form_med_reconcilation_brought_in For Id:' . $id, $_SESSION['authUserID']);
    } else {
        log_user_event('eMAR - Edit Meds Broughtin popup', 'Failed to Updated form_med_reconcilation_brought_in For Id:' . $id, $_SESSION['authUserID']);
    }


    $query = "update prescriptions SET dosage='" . $dosage . "',drug = '" . $medication . "', `interval` = '" . $frequency . "', quantity = '" . $amnt_on_hand . "',provider_id = '" . $approved_by . "',size  = '" . $size . "',unit  = '" . $unit . "',route  = '" . $route . "',form  = '" . $form . "',note  = '" . $warning_txt . "',med_time = '" . $med_time . "',updated_by  = '" . $_SESSION['authUserID'] . "',encounter= '" . $_SESSION['encounter'] . "',verbal ='" . $verbal . "',drug_id='" . $drug_id . "' where patient_id = '" . $pid . "' and med_brought_in_id = '" . $id . "' ";


    //$result = sqlStatement($query);

    if ($result = sqlStatement($query)) {
        log_user_event('eMAR - Edit Meds Broughtin popup', 'Updated prescriptions For Id:' . $id, $_SESSION['authUserID']);
    } else {
        log_user_event('eMAR - Edit Meds Broughtin popup', 'Failed to Updated prescriptions For Id:' . $id, $_SESSION['authUserID']);
    }

    $qry = "SELECT id,patient_id FROM prescriptions WHERE med_brought_in_id = '" . $id . "' and patient_id= '" . $pid . "' ";

    $pre_res = sqlStatement($qry);
    $pre_res_row = sqlFetchArray($pre_res);
    $prescription_id = isset($pre_res_row['id']) ? $pre_res_row['id'] : 0;
    if ($prescription_id != 0) {
        addMeds($prescription_id, true);
    }
    echo "<script type='text/javascript'>$(function () { dlgclose(); }); </script>";
}
?>


<body>
    <form id="edit_meds_broughtin" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="pid" value="<?= $pid ?>">
        <input type="hidden" name="edit_meds_broughtin" value="yes">
        <input type="hidden" name="encounter" value="<?= $encounter ?>">
        <div class="row mx-3">
            <div class="col-sm-12 px-2">
                <?php
                $sql_query = "SELECT * FROM `form_med_reconcilation_brought_in` where id='" . $id . "'";

                $res2 = sqlStatement($sql_query);
                while ($row2 = sqlFetchArray($res2)) { ?>
                    <!-- Loop start -->
                    <div class="row my-2">
                        <div class="col-6">
                            <p class="p-tag">Medication<input type="hidden" name="id" id="id" value=<?php echo $row2['id']; ?>></p>
                        </div>
                        <div class="col-6">
                            <input type="hidden" class="new_medication" id="broughtin_drug_id" name="drug_id" value="<?php echo $row2['drug_id'] ?>" />
                            <input type="text" name="medication" id="medication" value="<?php echo $row2['medication']; ?>" class="form-control broughtin_medication">
                            <div class="position-absolute zindex-fixed hideme medication_name_list " style="z-index:1500;">
                                <ul class="drugs_list_medication list-group" style="height: 300px; overflow-y:scroll;"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-3">
                            <p class="p-tag">Medicine Units</p>
                        </div>
                        <div class="col-3">
                            <input type="text" class="form-control" name="size" value="<?php echo $row2['size']; ?>">
                        </div>
                        <div class="col-3">
                            <select name="unit" class="form-control" readonly>
                                <?php
                                $sql_query = "SELECT title,option_id FROM `list_options` where list_id = 'drug_units' and activity = '1'";
                                $list_options_drug = sqlStatement($sql_query);
                                while ($drug_units = sqlFetchArray($list_options_drug)) { ?>
                                    <option value="<?php echo $drug_units['option_id'] ?>" <?php if ($drug_units['option_id'] == $row2['unit']) echo 'selected' ?>><?php echo $drug_units['title'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row my-2">

                        <div class="col-6">
                            <p class="p-tag">Directions</p>
                        </div>

                        <div class="col-6">
                            <input type="text" class="form-control" name="dosage" value="<?php echo $row2['dosage']; ?>">
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-6">
                            <p class="p-tag">Route</p>
                        </div>
                        <div class="col-6">
                            <select name="route" class="form-control" readonly>
                                <?php
                                $sql_query = "SELECT title,option_id FROM `list_options` where list_id = 'drug_route' and activity = '1'";
                                $list_options_route = sqlStatement($sql_query);
                                while ($drug_route = sqlFetchArray($list_options_route)) { ?>
                                    <option value="<?php echo $drug_route['option_id'] ?>" <?php if ($drug_route['option_id'] == $row2['route']) echo 'selected' ?>><?php echo $drug_route['title'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-6">
                            <p class="p-tag">Form</p>
                        </div>
                        <div class="col-6">
                            <select name="form" class="form-control" readonly>
                                <?php
                                $sql_query = "SELECT title,option_id FROM `list_options` where list_id = 'drug_form' and activity = '1'";
                                $list_options_form = sqlStatement($sql_query);
                                while ($drug_form = sqlFetchArray($list_options_form)) { ?>
                                    <option value="<?php echo $drug_form['option_id'] ?>" <?php if ($drug_form['option_id'] == $row2['form']) echo 'selected' ?>><?php echo $drug_form['title'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <?php if ($row2['broughtin_status'] == '0') { ?>
                        <div class="row my-2">
                            <div class="col-6">
                                <p class="p-tag">Hold</p>
                            </div>
                            <div class="col-6">
                                <input type="hidden" class="hid_hold" name="hold" value="0">
                                <input class="form-check-input hid_hold" id="hold" type="checkbox" name="hold" value="1" <?php if ($row2['hold'] == '1') echo 'checked'; ?>>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row my-2">
                        <div class="col-3">
                            <p class="p-tag">Frequency</p>
                        </div>
                        <div class="col-5">
                            <select name="frequency" class="form-control frequency_interval" readonly>
                                <?php
                                $sql_query = "SELECT title,option_id FROM `list_options` where list_id = 'drug_interval' and activity = '1'";
                                $fq_res = sqlStatement($sql_query);
                                while ($fq_result = sqlFetchArray($fq_res)) { ?>
                                    <option value="<?php echo $fq_result['option_id'] ?>" <?php if ($fq_result['option_id'] == $row2['frequency']) echo 'selected' ?>><?php echo $fq_result['title'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-4">
                            <p class="p-tag">
                                <input type="checkbox" name="is_prn" id="" value='1' <?php if ($row2['is_prn'] == 1) echo 'checked' ?>> Is Prn
                            </p>
                        </div>
                    </div>
                    <?php $times = explode(',', $row2['med_time']); ?>
                    <div class="row my-2">
                        <div class="col-md-1 text-center px-0 mr-auto">
                            <p class="med_edit_time_label" for="med_edit_time_1">Time 1</p>
                            <input type="text" size=5 class="form-control med_edit_time med_edit_time_1" id="med_edit_time_1" name="med_time[]" value="<?= $times[0] ?>" />
                        </div>

                        <div class="col-md-1 text-center px-0 mr-auto">
                            <p class="med_edit_time_label" for="med_edit_time_2">Time 2</p>
                            <input type="text" size=5 class="form-control med_edit_time med_edit_time_2" id="med_edit_time_2" name="med_time[]" value="<?= $times[1] ?>" />
                        </div>
                        <div class="col-md-1 text-center px-0 mr-auto">
                            <p class="med_edit_time_label" for="med_edit_time_3">Time 3</p>
                            <input type="text" size=5 class="form-control med_edit_time med_edit_time_3" id="med_edit_time_3" name="med_time[]" value="<?= $times[2] ?>" />
                        </div>

                        <div class="col-md-1 text-center px-0 mr-auto">
                            <p class="med_edit_time_label" for="med_edit_time_4">Time 4</p>
                            <input type="text" size=5 class="form-control med_edit_time med_edit_time_4" id="med_edit_time_4" name="med_time[]" value="<?= $times[3] ?>" />
                        </div>

                        <div class="col-md-1 text-center px-0 mr-auto">
                            <p class="med_edit_time_label" for="med_edit_time_5">Time 5</p>
                            <input type="text" size=5 class="form-control med_edit_time med_edit_time_5" id="med_edit_time_5" name="med_time[]" value="<?= $times[4] ?>" />
                        </div>

                        <div class="col-md-1 text-center px-0 mr-auto">
                            <p class="med_edit_time_label" for="med_edit_time_6">Time 6</p>
                            <input type="text" size=5 class="form-control med_edit_time med_edit_time_6" id="med_edit_time_6" name="med_time[]" value="<?= $times[5] ?>" />
                        </div>

                        <div class="col-md-1 text-center px-0 mr-auto">
                            <p class="med_edit_time_label" for="med_edit_time_7">Time 7</p>
                            <input type="text" size=5 class="form-control med_edit_time med_edit_time_7" id="med_edit_time_7" name="med_time[]" value="<?= $times[6] ?>" />
                        </div>

                        <div class="col-md-1 text-center px-0 mr-auto">
                            <p class="med_edit_time_label" for="med_edit_time_8">Time 8</p>
                            <input type="text" size=5 class="form-control med_edit_time med_edit_time_8" id="med_edit_time_8" name="med_time[]" value="<?= $times[7] ?>" />
                        </div>

                    </div>
                    <div class="row my-2">
                        <div class="col-6">
                            <p class="p-tag">Amount on Hand</p>
                        </div>
                        <div class="col-6">
                            <input type="text" name="amnt_on_hand" id="amnt_on_hand" value="<?php echo $row2['amnt_on_hand'];  ?>" class="form-control">
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-6">
                            <p class="p-tag">Last taken</p>
                        </div>

                        <div class="col-6">

                            <input type="text" name="last_taken" id="last_taken" value="<?= ($row2['last_taken']) ? $row2['last_taken'] : ''; ?>" class="form-control datepicker1">
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-6">
                            <p class="p-tag">Prescribed By</p>
                        </div>
                        <div class="col-6">
                            <input type="text" name="prescribe" id="prescribe" value="<?php echo $row2['prescribe'];  ?>" class="form-control">
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-6">
                            <p class="p-tag">Logged By</p>
                        </div>
                        <div class="col-6">
                            <input type="text" name="logged_by" id="logged_by" value="<?php echo $row2['logged_by'];  ?>" class="form-control">
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-6">
                            <p class="p-tag">Approved By</p>
                        </div>
                        <div class="col-6">
                            <select name="approved_by" class="form-control">
                                <?php $approved_flora = $GLOBALS['approved_by']; ?>
                                <?php
                                foreach ($approved_flora as $key => $value) { ?>
                                    <option value="<?php echo $key ?>" <?php if ($row2['approved_by'] == $key) {
                                                                            echo "selected";
                                                                        } ?>><?php echo $value ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-6">
                            <p class="p-tag">Verbal</p>
                        </div>
                        <div class="col-6">
                            <select name="verbal" class="form-control">
                                <option value=""></option>
                                <option value="by_phone" <?php if ($row2['verbal'] == 'by_phone') {
                                                                echo "selected";
                                                            } ?>>By phone</option>
                                <option value="in_person" <?php if ($row2['verbal'] == 'in_person') {
                                                                echo "selected";
                                                            } ?>>In person</option>
                            </select>
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-6">
                            <p class="p-tag">Amount Returned</p>
                        </div>
                        <div class="col-6">
                            <input type="number" class="form-control" name="amt_returned" id="amt_returned" value="<?php echo $row2['amt_returned'];  ?>">
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-6">
                            <p class="p-tag">Date and Time</p>
                        </div>

                        <div class="col-6">

                            <input type="text" name="time" id="time" class="form-control datepicker1 " value="<?= ($row2['time']) ? $row2['time'] : ''; ?>">
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-6">
                            <p class="p-tag">Amount Destroyed</p>
                        </div>
                        <div class="col-6">
                            <input type="number" name="amount_destroyed" class="form-control" id="amount_destroyed" class="form-control" value="<?php echo $row2['amount_destroyed'];  ?>">
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-6">
                            <p class="p-tag">Continue On Discharge</p>
                        </div>
                        <div class="col-6">
                            <input type="hidden" class="hid_continue_discharge" name="continue_discharge" value="0">
                            <input class="form-check-input hid_continue_discharge" id="continue_discharge" type="checkbox" name="continue_discharge" value="1" <?php if ($row2['continue_discharge'] == '1') echo 'checked'; ?>>
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-6">
                            <p class="p-tag">Witness</p>
                        </div>
                        <div class="col-6">
                            <input type="text" name="witness" id="witness" value="<?php echo $row2['witness'];  ?>" class="form-control">
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-6">
                            <p class="p-tag">Warning Text</p>
                        </div>
                        <div class="col-6">
                            <textarea type="text" name="warning_txt" id="warning_txt" class="form-control"><?php echo $row2['warning_txt'];  ?></textarea>
                        </div>
                    </div>
                <?php  } ?>
                <!-- Loop end -->
            </div>
        </div>

    </form>

    <div class="modal-footer">

        <button type="button" class="btn btn-primary ml-auto" id="btn_medlogs_submit" onClick="submitForm();">Update</button>
    </div>
</body>

<script>
    $(document).ready(function() {

        $('body').on('keypress', '.broughtin_medication', function() {
            var drug_name = $(this).val();
            var $t = $(this);
            $.ajax({
                url: '/library/ajax/drug_autocomplete/search_orderset.php?csrf_token_form=<?php echo $csrf_token ?>&term=' + drug_name,
                type: 'GET',
                success: function(data) {
                    var json = JSON.parse(data);

                    var $list = $t.parents().find('.drugs_list_medication');
                    $list.empty();
                    $t.parents().find('.medication_name_list').show();
                    $t.parents().find('.medication_name_list').removeClass('d-none');
                    // $('#drug_name_list').html(data);
                    for (var i in json)
                        $list.append('<li class="list-group-item brougthin_drug_item" id="' + i + '">' + json[i] + '</li>');

                }
            });
        });

        $('body').on('click', '.brougthin_drug_item', function() {
            $(this).parents().find('.broughtin_medication').val($(this).text());
            $(this).parents().find('#broughtin_drug_id').val($(this).attr('id'));
            $(this).parents().find('.drugs_list_medication').hide();
        });

    });


    function submitForm() {
        var form = document.getElementById("edit_meds_broughtin");
        form.submit();
    }

    window.addEventListener('load',
        function() {
            // alert('hello!');
            $('.frequency_interval').trigger('change');
        }, false);

    $('.frequency_interval').trigger('change');

    $('body').on('change', '.frequency_interval', function() {
        hideEditAllTimeBox();
        set_tiime_edit_boxes($(this).val())
    });

    function set_tiime_edit_boxes(choice) {
        switch (choice) {

            case '4':
                $('.med_edit_time_label').show();
                $('.med_edit_time').show();
                $('#med_edit_time_container').css('padding-right', '0px');
                $('.med_edit_time_5').val('20:00');
                $('.med_edit_time_6').val('23:00');
                $('.med_edit_time_7').val('02:00');
                $('.med_edit_time_8').val('05:00');
                $('.med_edit_time_1').val('08:00');
                $('.med_edit_time_2').val('11:00');
                $('.med_edit_time_3').val('14:00');
                $('.med_edit_time_4').val('17:00');

                break;

            case '5':
                $('.med_edit_time').show();
                $('.med_edit_time_label').show();
                $('.med_edit_time_8').hide();
                $('.med_edit_time_7').hide();

                $('.med_edit_time_5').val('00:00');
                $('.med_edit_time_6').val('04:00');
                $('.med_edit_time_1').val('08:00');
                $('.med_edit_time_2').val('12:00');
                $('.med_edit_time_3').val('16:00');
                $('.med_edit_time_4').val('20:00');
                break;

            case '6':
                $('.med_edit_time_1').show();
                $('.med_edit_time_2').show();
                $('.med_edit_time_3').show();
                $('.med_edit_time_4').show();
                $('.med_edit_time_5').show();
                $('.med_edit_time_4').val('23:00');
                $('.med_edit_time_5').val('04:00');
                $('.med_edit_time_1').val('08:00');
                $('.med_edit_time_2').val('13:00');
                $('.med_edit_time_3').val('18:00');
                break;

            case '7':
                $('.med_edit_time_1').show();
                $('.med_edit_time_2').show();
                $('.med_edit_time_3').show();
                $('.med_edit_time_4').show();
                $('.med_edit_time_4').val('02:00');
                $('.med_edit_time_1').val('08:00');
                $('.med_edit_time_2').val('14:00');
                $('.med_edit_time_3').val('20:00');
                break;

            case '3':
                $('.med_edit_time_1').show();
                $('.med_edit_time_2').show();
                $('.med_edit_time_3').show();
                $('.med_edit_time_4').show();
                $('.med_edit_time_4').val('02:00');
                $('.med_edit_time_1').val('08:00');
                $('.med_edit_time_2').val('14:00');
                $('.med_edit_time_3').val('20:00');
                break;

            case '8':
                $('.med_edit_time_1').show();
                $('.med_edit_time_2').show();
                $('.med_edit_time_3').show();
                $('.med_edit_time_3').val('00:00');
                $('.med_edit_time_1').val('08:00');
                $('.med_edit_time_2').val('16:00');
                break;

            case '2':
                $('.med_edit_time_1').show();
                $('.med_edit_time_2').show();
                $('.med_edit_time_3').show();
                $('.med_edit_time_3').val('00:00');
                $('.med_edit_time_1').val('08:00');
                $('.med_edit_time_2').val('16:00');
                break;
            case '1':
                $('.med_edit_time_1').show();
                $('.med_edit_time_2').show();
                $('.med_edit_time_1').val('08:00');
                $('.med_edit_time_2').val('20:00');
                break;
            case '9':
                ;
            default:
                $('.med_edit_time_1').show();
                $('.med_edit_time_1').val('08:00');
        }
    }

    $('.med_edit_time').datetimepicker({
        datepicker: false,
        format: 'H:i',
    });

    function hideEditAllTimeBox() {
        $('.med_edit_time').hide();
        $('.med_time_label').hide();

        $('.med_edit_time_1').val('');
        $('.med_edit_time_2').val('');
        $('.med_edit_time_3').val('');
        $('.med_edit_time_4').val('');
        $('.med_edit_time_5').val('');
        $('.med_edit_time_6').val('');
        $('.med_edit_time_7').val('');
        $('.med_edit_time_8').val('');

    }
    $('.datepicker1').datetimepicker({
        timepicker: true,
        format: 'm/d/Y H:i:s'
    })
</script>