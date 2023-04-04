<script>
    $(document).ready(function() {
        $('#doctor_vital_check').on('change', function() {
            if ($(this).val() == 'other') {
                $('#doctor_vital_check_other').show();
            } else {
                $('#doctor_vital_check_other').hide();
            }
        });

        $('#add_vital_check').on('hide.bs.modal', function() {
            $('#doctors_orders_form').trigger("reset");
        });

        $('#doctors_order_multi').on('change', function() {
            if (this.checked) {
                $('div#meds_pres').find('input.doctors_order_single').prop('checked', true);
            } else {
                $('div#meds_pres').find('input.doctors_order_single').prop('checked', false);
            }
        });

        $('.discontinue_doc_orders').on('click', function() {
            $("#discontinue_doc_orders_modal").modal()
        });

        $('.discontinue_doc_orders_final_btn').on('click', function() {
            var ids = [];
            let serverUrl = "disable_medication.php";

            $doc_order_discontinuation_reason = $('#doc_order_discontinuation_reason').val();
            $doc_order_provider_id = $('#doc_order_provider_id').val();
            $doc_order_verbal = $('#doc_order_verbal').val();

            $(".doctors_order_single").each(function() {
                if (this.checked) {
                    ids.push($(this).data('id'));
                }
            });

            if ($doc_order_discontinuation_reason !== "" && $doc_order_provider_id !== '0' && $doc_order_verbal !== '0') {
                let Ids = ids.toString();
                if (ids.length > 0) {
                    if (confirm("Do You Want to Discontinue Doctor's Orders?")) {
                        $.ajax({
                            url: serverUrl,
                            type: "POST",
                            data: {
                                'discontinue_doc_orders': 'yEs',
                                'ids': Ids,
                                'doc_order_discontinuation_reason': $doc_order_discontinuation_reason,
                                'doc_order_provider_id': $doc_order_provider_id,
                                'doc_order_verbal': $doc_order_verbal
                            },
                            success: function(response) {
                                window.location.reload();
                            }
                        });
                    }
                } else {
                    alert("Please Select Doctors Orders to Discontinue");
                }
            } else {
                alert("Please Select Discontinuation Reason,Provider and Verbal Order");
            }

        });

        $('.disable_order_set').on('change', function() {
            $order_set_id = $(this).data('order_set_id');

            if (this.checked) {
                $('div#' + $order_set_id).find('input.disable_meds').prop('checked', true);
            } else {
                $('div#' + $order_set_id).find('input.disable_meds').prop('checked', false);
            }
        });

        $('.disable_other_meds').on('change', function() {
            if (this.checked) {
                $('div#other_meds').find('input.disable_meds').prop('checked', true);
            } else {
                $('div#other_meds').find('input.disable_meds').prop('checked', false);
            }
        });

        $('.disable_medication_btn').on('click', function() {
            var ids = [];
            let serverUrl = "disable_medication.php";

            $discontinuation_reason = $('#discontinuation_reason').val();
            $provider_id = $('#provider_id').val();
            $verbal = $('#verbal').val();

            if ($discontinuation_reason !== "" && $provider_id !== '0' && $verbal !== '0') {
                $(".disable_meds").each(function() {
                    if (this.checked) {
                        ids.push($(this).data('id'));
                    }
                });
                let Ids = ids.toString();
                if (ids.length > 0) {
                    $.ajax({
                        url: serverUrl,
                        type: "POST",
                        data: {
                            'disable_meds': 'yEs',
                            'ids': Ids,
                            'discontinuation_reason': $discontinuation_reason,
                            'provider_id': $provider_id,
                            'verbal': $verbal
                        },
                        success: function(response) {
                            window.location.reload();
                        }
                    });
                } else {
                    alert("Please Select Medication to Discontinue");
                }
            } else {
                if ($provider_id == 77 || $provider_id == 141) {
                    alert("Please Select Discontinuation Reason");
                } else {
                    alert("Please Select Discontinuation Reason,Provider and Verbal Order");
                }
            }

        });

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

        $('.check_list').on('click', function() {
            let serverUrl = "check_administered_warning.php?csrf_token_form=<?= $csrf_token; ?>&prescription_id=" + $(this).data('prescription_id');
            if ($(this).prop('checked') && $(this).data('is_prn')) {
                $.ajax({
                    url: serverUrl,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        if (response[1] >= response[0])
                            alert('This Medication is administered more than instructed');
                    }
                });
            }
        });

        $('.continue_on_discharge').each(function() {
            if ($(this).attr('data-val') == 1) {
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }
        });

        $('#add_medsbrought_form').on('click', function() {
            let serverUrl = "meds_brought_in.php?csrf_token_form=<?= $csrf_token; ?>";
            console.log(serverUrl);
            $.ajax({
                url: serverUrl,
                type: "POST",
                data: $('#add_medsbrought_form_name').serialize(),
                success: function(response) {
                    window.location.reload();
                    console.log('updated' + response);
                }
            });
        });

        $('.continue_on_discharge').on('click', function() {
            let serverUrl = "update_prescription.php?csrf_token_form=<?= $csrf_token; ?>";
            let cod = ($(this).is(":checked")) ? 1 : 0;

            $.ajax({
                url: serverUrl,
                type: "POST",
                data: {
                    'med_id': $(this).attr('data-id'),
                    'continue_on_discharge': cod
                },
                success: function(response) {
                    console.log('updated');
                }
            });
        });

        //filter_upcoming_med

        $('#filter_upcoming_med').on('click', function() {
            console.log($('#up_from_date').val());
            console.log($('#up_end_date').val());
            let serverUrl = "get_upcoming_prescriptions.php?csrf_token_form=<?= $csrf_token; ?>&pid=<?= $pid ?>&from_date=" + $('#up_from_date').val() + "&to-date=" + $('#up_end_date').val();

            $.ajax({
                url: serverUrl,
                type: "GET",
                success: function(response) {
                    $('#DataTables_Table_2 tbody').html(response)
                }
            });

        });

        $('#filter_prescribed_med').on('click', function() {
            console.log($('#find_from_date').val());
            console.log($('#find_end_date').val());
            let serverUrl = "get_prescriptions.php?csrf_token_form=<?= $csrf_token; ?>&pid=<?= $pid ?>&from_date=" + $('#find_from_date').val() + "&to-date=" + $('#find_end_date').val();

            $.ajax({
                url: serverUrl,
                type: "GET",
                success: function(response) {
                    $('#DataTables_Table_3 tbody').html(response)
                }
            });

        });

        $('.staff_note').on('change', function() {
            let serverUrl = "updated_staff_noted.php?csrf_token_form=<?= $csrf_token; ?>";
            $(this).parent().html($(this).val());
            $.ajax({
                url: serverUrl,
                type: "POST",
                data: {
                    'med_id': $(this).attr('data-id'),
                    'staff_note': $(this).val()
                },
                success: function(response) {

                    console.log('Result response : ' + response);
                }
            });

        });

        $('table.e_MAR_tb_current_med').DataTable({
            bAutoWidth: false,
            "pageLength": 100,
            "ordering": false,
            dom: 'l<"toolbar">frtip',
            initComplete: function() {
                $("div.toolbar")
                    .css({
                        "float": "left"
                    })
                    .html('<button href="javascript;;" class="rx_modal btn-sm btn btn-primary btn_multimeds_administrate <?= $disable_add_btns ?>"   disabled=true onclick="editAdminstrate(1)" style="color:black">Administer</button> ' +
                        '');
            },
        });


        $('table.e_MAR_tb_prescription_upcoming').DataTable({
            "pageLength": 100,
            "ordering": false,
            bAutoWidth: false,
            columnDefs: [{
                class: 'drug_header',
                targets: 0
            }, {
                class: 'date_header',
                targets: 1
            }, {
                class: 'medtime_header',
                targets: 2
            }],
        });

        $('table.e_MAR_tb-1').DataTable({
            "pageLength": 100,
            order: [
                [0, 'asc']
            ],
        });


        $('table.e_MAR_tb_prescription').DataTable({
            bAutoWidth: false,
            "pageLength": 100,
            columnDefs: [{
                class: 'drug_header',
                targets: 0
            }, {
                class: 'date_header',
                targets: 1
            }, {
                class: 'medtime_header',
                targets: 2
            }],
        });

        $('table.e_MAR_tb').DataTable({
            "pageLength": 100,
        });

        $('table.e_MAR_tb_iRx').DataTable({
            "pageLength": 100,
        });

        $('.staff_note_enter').on('change', function() {
            id = $(this).data("id");
            var note = this.value;

            var idv = "staff_note_paste_" + id;
            document.getElementById(idv).value = note;
        });

        $('select.pain_scale_sel').on('change', function() {
            id = $(this).data("id");
            var note = this.value;

            var idv = "pain_scalef_" + id;
            document.getElementById(idv).value = note;
        });

        $('.glucose_reading').on('change', function() {
            id = $(this).data("id");
            //var id = $(this).attr('data-id');
            var note = this.value;

            var idv = "glucosef_" + id;
            document.getElementById(idv).value = note;
        });

        // $('#add_meds_brought_in').on('click', function() {
        // 	console.log("testing");
        // 	$('#add_meds').modal('show')
        // });

        $('#edit_meds_brought_in').on('click', function() {
            console.log("testing");
            $('#edit_meds').modal('show')
        });

        $('#vital_check').on('click', function() {

            $('#add_vital_check').modal('show')
            $('.datepicker').datetimepicker({
                format: 'm/d/Y H:i',
            });

            $('.datepicker12').datetimepicker({
                format: 'm/d/Y H:i',
                minDate: 0,
            });

        });


        $('.dateonlypicker').datetimepicker({
            format: 'm/d/Y',
            timepicker: false,
        });

        $('#add_vitals').on('click', function() {
            console.log("testing");
            $('#add_vitals_index').modal('show')
        });

        $('#add_allergies').on('click', function() {
            console.log("testing");
            $('#add_allergies_box').modal('show')
        });

        $('.eMAR_sb').on('click', function() {
            id = $(this).data("today_meds");

            $(".eMAR_sb").removeClass("active")
            var element = document.getElementById(id);
            element.classList.add("active");
            if (id == 1) {
                document.getElementById("today_meds").style.display = "block";
                document.getElementById("meds_pres").style.display = "none";
                document.getElementById("amed").style.display = "none";
                document.getElementById("meds_admin").style.display = "none";
                document.getElementById("meds_broughtin").style.display = "none";
                document.getElementById("allergies").style.display = "none";
                document.getElementById("vitals").style.display = "none";
                document.getElementById("cows").style.display = "none";
                document.getElementById("ciwa").style.display = "none";
                document.getElementById("ciwa_b").style.display = "none";
                document.getElementById("pain_asses").style.display = "none";
                document.getElementById("meds_not_admin").style.display = "none";
                document.getElementById("upcoming_med_logs").style.display = "none";
                document.getElementById("verbal_orders").style.display = "none";
                document.getElementById("iRx_meds").style.display = "none";
            } else if (id == 2) {
                document.getElementById("today_meds").style.display = "none";
                document.getElementById("meds_pres").style.display = "block";
                document.getElementById("amed").style.display = "block";
                document.getElementById("meds_admin").style.display = "none";
                document.getElementById("meds_broughtin").style.display = "none";
                document.getElementById("allergies").style.display = "none";
                document.getElementById("vitals").style.display = "none";
                document.getElementById("cows").style.display = "none";
                document.getElementById("ciwa").style.display = "none";
                document.getElementById("ciwa_b").style.display = "none";
                document.getElementById("pain_asses").style.display = "none";
                document.getElementById("meds_not_admin").style.display = "none";
                document.getElementById("upcoming_med_logs").style.display = "none";
                document.getElementById("verbal_orders").style.display = "none";
                document.getElementById("iRx_meds").style.display = "none";
            } else if (id == 3) {
                document.getElementById("today_meds").style.display = "none";
                document.getElementById("meds_pres").style.display = "none";
                document.getElementById("meds_admin").style.display = "block";
                document.getElementById("meds_broughtin").style.display = "none";
                document.getElementById("allergies").style.display = "none";
                document.getElementById("vitals").style.display = "none";
                document.getElementById("cows").style.display = "none";
                document.getElementById("ciwa").style.display = "none";
                document.getElementById("ciwa_b").style.display = "none";
                document.getElementById("pain_asses").style.display = "none";
                document.getElementById("amed").style.display = "none";
                document.getElementById("meds_not_admin").style.display = "none";
                document.getElementById("upcoming_med_logs").style.display = "none";
                document.getElementById("verbal_orders").style.display = "none";
                document.getElementById("iRx_meds").style.display = "none";
            } else if (id == 4) {
                document.getElementById("today_meds").style.display = "none";
                document.getElementById("meds_pres").style.display = "none";
                document.getElementById("meds_admin").style.display = "none";
                document.getElementById("meds_broughtin").style.display = "none";
                document.getElementById("allergies").style.display = "block";
                document.getElementById("vitals").style.display = "none";
                document.getElementById("cows").style.display = "none";
                document.getElementById("ciwa").style.display = "none";
                document.getElementById("pain_asses").style.display = "none";
                document.getElementById("ciwa_b").style.display = "none";
                document.getElementById("amed").style.display = "none";
                document.getElementById("meds_not_admin").style.display = "none";
                document.getElementById("upcoming_med_logs").style.display = "none";
                document.getElementById("verbal_orders").style.display = "none";
                document.getElementById("iRx_meds").style.display = "none";
            } else if (id == 5) {
                document.getElementById("today_meds").style.display = "none";
                document.getElementById("meds_pres").style.display = "none";
                document.getElementById("meds_admin").style.display = "none";
                document.getElementById("meds_broughtin").style.display = "none";
                document.getElementById("allergies").style.display = "none";
                document.getElementById("vitals").style.display = "block";
                document.getElementById("cows").style.display = "none";
                document.getElementById("ciwa").style.display = "none";
                document.getElementById("pain_asses").style.display = "none";
                document.getElementById("ciwa_b").style.display = "none";
                document.getElementById("amed").style.display = "none";
                document.getElementById("meds_not_admin").style.display = "none";
                document.getElementById("upcoming_med_logs").style.display = "none";
                document.getElementById("verbal_orders").style.display = "none";
                document.getElementById("iRx_meds").style.display = "none";
            } else if (id == 6) {
                document.getElementById("today_meds").style.display = "none";
                document.getElementById("meds_pres").style.display = "none";
                document.getElementById("meds_admin").style.display = "none";
                document.getElementById("meds_broughtin").style.display = "none";
                document.getElementById("allergies").style.display = "none";
                document.getElementById("vitals").style.display = "none";
                document.getElementById("cows").style.display = "block";
                document.getElementById("ciwa").style.display = "none";
                document.getElementById("pain_asses").style.display = "none";
                document.getElementById("ciwa_b").style.display = "none";
                document.getElementById("amed").style.display = "none";
                document.getElementById("meds_not_admin").style.display = "none";
                document.getElementById("upcoming_med_logs").style.display = "none";
                document.getElementById("verbal_orders").style.display = "none";
                document.getElementById("iRx_meds").style.display = "none";
            } else if (id == 7) {
                document.getElementById("today_meds").style.display = "none";
                document.getElementById("meds_pres").style.display = "none";
                document.getElementById("meds_admin").style.display = "none";
                document.getElementById("meds_broughtin").style.display = "none";
                document.getElementById("allergies").style.display = "none";
                document.getElementById("vitals").style.display = "none";
                document.getElementById("cows").style.display = "none";
                document.getElementById("ciwa").style.display = "block";
                document.getElementById("pain_asses").style.display = "none";
                document.getElementById("ciwa_b").style.display = "none";
                document.getElementById("amed").style.display = "none";
                document.getElementById("meds_not_admin").style.display = "none";
                document.getElementById("upcoming_med_logs").style.display = "none";
                document.getElementById("verbal_orders").style.display = "none";
                document.getElementById("iRx_meds").style.display = "none";
            } else if (id == 8) {
                document.getElementById("today_meds").style.display = "none";
                document.getElementById("meds_pres").style.display = "none";
                document.getElementById("meds_admin").style.display = "none";
                document.getElementById("meds_broughtin").style.display = "none";
                document.getElementById("allergies").style.display = "none";
                document.getElementById("vitals").style.display = "none";
                document.getElementById("cows").style.display = "none";
                document.getElementById("ciwa").style.display = "none";
                document.getElementById("ciwa_b").style.display = "none";
                document.getElementById("pain_asses").style.display = "none";
                document.getElementById("amed").style.display = "none";
                document.getElementById("meds_not_admin").style.display = "none";
                document.getElementById("pain_asses").style.display = "block";
                document.getElementById("upcoming_med_logs").style.display = "none";
                document.getElementById("verbal_orders").style.display = "none";
                document.getElementById("iRx_meds").style.display = "none";
            } else if (id == 9) {
                document.getElementById("today_meds").style.display = "none";
                document.getElementById("meds_pres").style.display = "none";
                document.getElementById("meds_admin").style.display = "none";
                document.getElementById("meds_broughtin").style.display = "block";
                document.getElementById("allergies").style.display = "none";
                document.getElementById("vitals").style.display = "none";
                document.getElementById("cows").style.display = "none";
                document.getElementById("ciwa").style.display = "none";
                document.getElementById("ciwa_b").style.display = "none";
                document.getElementById("pain_asses").style.display = "none";
                document.getElementById("amed").style.display = "none";
                document.getElementById("meds_not_admin").style.display = "none";
                document.getElementById("upcoming_med_logs").style.display = "none";
                document.getElementById("verbal_orders").style.display = "none";
                document.getElementById("iRx_meds").style.display = "none";
            } else if (id == 10) {
                document.getElementById("today_meds").style.display = "none";
                document.getElementById("meds_pres").style.display = "none";
                document.getElementById("meds_admin").style.display = "none";
                document.getElementById("meds_broughtin").style.display = "none";
                document.getElementById("allergies").style.display = "none";
                document.getElementById("vitals").style.display = "none";
                document.getElementById("cows").style.display = "none";
                document.getElementById("ciwa").style.display = "none";
                document.getElementById("ciwa_b").style.display = "block";
                document.getElementById("pain_asses").style.display = "none";
                document.getElementById("amed").style.display = "none";
                document.getElementById("meds_not_admin").style.display = "none";
                document.getElementById("upcoming_med_logs").style.display = "none";
                document.getElementById("verbal_orders").style.display = "none";
                document.getElementById("iRx_meds").style.display = "none";
            } else if (id == 11) {
                document.getElementById("today_meds").style.display = "none";
                document.getElementById("meds_pres").style.display = "none";
                document.getElementById("meds_admin").style.display = "none";
                document.getElementById("meds_broughtin").style.display = "none";
                document.getElementById("allergies").style.display = "none";
                document.getElementById("vitals").style.display = "none";
                document.getElementById("cows").style.display = "none";
                document.getElementById("ciwa").style.display = "none";
                document.getElementById("ciwa_b").style.display = "none";
                document.getElementById("pain_asses").style.display = "none";
                document.getElementById("amed").style.display = "none";
                document.getElementById("meds_not_admin").style.display = "block";
                document.getElementById("upcoming_med_logs").style.display = "none";
                document.getElementById("verbal_orders").style.display = "none";
                document.getElementById("iRx_meds").style.display = "none";
            } else if (id == 12) {
                document.getElementById("today_meds").style.display = "none";
                document.getElementById("meds_pres").style.display = "none";
                document.getElementById("meds_admin").style.display = "none";
                document.getElementById("meds_broughtin").style.display = "none";
                document.getElementById("allergies").style.display = "none";
                document.getElementById("vitals").style.display = "none";
                document.getElementById("cows").style.display = "none";
                document.getElementById("ciwa").style.display = "none";
                document.getElementById("ciwa_b").style.display = "none";
                document.getElementById("pain_asses").style.display = "none";
                document.getElementById("amed").style.display = "none";
                document.getElementById("meds_not_admin").style.display = "none";
                document.getElementById("upcoming_med_logs").style.display = "block";
                document.getElementById("verbal_orders").style.display = "none";
                document.getElementById("iRx_meds").style.display = "none";
            } else if (id == 13) {
                document.getElementById("today_meds").style.display = "none";
                document.getElementById("meds_pres").style.display = "none";
                document.getElementById("meds_admin").style.display = "none";
                document.getElementById("meds_broughtin").style.display = "none";
                document.getElementById("allergies").style.display = "none";
                document.getElementById("vitals").style.display = "none";
                document.getElementById("cows").style.display = "none";
                document.getElementById("ciwa").style.display = "none";
                document.getElementById("ciwa_b").style.display = "none";
                document.getElementById("pain_asses").style.display = "none";
                document.getElementById("amed").style.display = "none";
                document.getElementById("meds_not_admin").style.display = "none";
                document.getElementById("upcoming_med_logs").style.display = "none";
                document.getElementById("verbal_orders").style.display = "block";
                document.getElementById("iRx_meds").style.display = "none";
            } else if (id == 14) {
                document.getElementById("today_meds").style.display = "none";
                document.getElementById("meds_pres").style.display = "none";
                document.getElementById("meds_admin").style.display = "none";
                document.getElementById("meds_broughtin").style.display = "none";
                document.getElementById("allergies").style.display = "none";
                document.getElementById("vitals").style.display = "none";
                document.getElementById("cows").style.display = "none";
                document.getElementById("ciwa").style.display = "none";
                document.getElementById("ciwa_b").style.display = "none";
                document.getElementById("pain_asses").style.display = "none";
                document.getElementById("amed").style.display = "none";
                document.getElementById("meds_not_admin").style.display = "none";
                document.getElementById("upcoming_med_logs").style.display = "none";
                document.getElementById("verbal_orders").style.display = "none";
                document.getElementById("iRx_meds").style.display = "block";
            }
        });

        $('.interval').trigger('change');

        $('body').on('change', '.interval', function() {
            hideAllTimeBox();
            set_tiime_boxes($(this).val())

        });

        function set_tiime_boxes(choice) {
            switch (choice) {

                case '4':
                    $('.med_time_label').show();
                    $('.med_time').show();
                    $('#med_time_container').css('padding-right', '145px');
                    $('.med_time_5').val('20:00');
                    $('.med_time_6').val('23:00');
                    $('.med_time_7').val('02:00');
                    $('.med_time_8').val('05:00');
                    $('.med_time_1').val('08:00');
                    $('.med_time_2').val('11:00');
                    $('.med_time_3').val('14:00');
                    $('.med_time_4').val('17:00');

                    break;

                case '5':
                    $('.med_time').show();
                    $('.med_time_label').show();
                    $('.med_time_8').hide();
                    $('.med_time_7').hide();

                    $('.med_time_5').val('00:00');
                    $('.med_time_6').val('04:00');
                    $('.med_time_1').val('08:00');
                    $('.med_time_2').val('12:00');
                    $('.med_time_3').val('16:00');
                    $('.med_time_4').val('20:00');
                    break;

                case '6':
                    $('.med_time_1').show();
                    $('.med_time_2').show();
                    $('.med_time_3').show();
                    $('.med_time_4').show();
                    $('.med_time_5').show();
                    $('.med_time_4').val('23:00');
                    $('.med_time_5').val('04:00');
                    $('.med_time_1').val('08:00');
                    $('.med_time_2').val('13:00');
                    $('.med_time_3').val('18:00');
                    break;

                case '7':
                    $('.med_time_1').show();
                    $('.med_time_2').show();
                    $('.med_time_3').show();
                    $('.med_time_4').show();
                    $('.med_time_4').val('02:00');
                    $('.med_time_1').val('08:00');
                    $('.med_time_2').val('14:00');
                    $('.med_time_3').val('20:00');
                    break;

                case '3':
                    $('.med_time_1').show();
                    $('.med_time_2').show();
                    $('.med_time_3').show();
                    $('.med_time_4').show();
                    $('.med_time_4').val('02:00');
                    $('.med_time_1').val('08:00');
                    $('.med_time_2').val('14:00');
                    $('.med_time_3').val('20:00');
                    break;

                case '8':
                    $('.med_time_1').show();
                    $('.med_time_2').show();
                    $('.med_time_3').show();
                    $('.med_time_3').val('00:00');
                    $('.med_time_1').val('08:00');
                    $('.med_time_2').val('16:00');
                    break;

                case '2':
                    $('.med_time_1').show();
                    $('.med_time_2').show();
                    $('.med_time_3').show();
                    $('.med_time_3').val('00:00');
                    $('.med_time_1').val('08:00');
                    $('.med_time_2').val('16:00');
                    break;
                case '1':
                    $('.med_time_1').show();
                    $('.med_time_2').show();
                    $('.med_time_1').val('08:00');
                    $('.med_time_2').val('20:00');
                    break;
                case '9':
                    ;
                default:
                    $('.med_time_1').show();
                    $('.med_time_1').val('08:00');
            }
        }

        $('.med_time').datetimepicker({
            datepicker: false,
            format: 'H:i'
        });

        function hideAllTimeBox() {
            $('.med_time').hide();
            $('.med_time_label').hide();
            $('.med_time_1').val('');
            $('.med_time_2').val('');
            $('.med_time_3').val('');
            $('.med_time_4').val('');
            $('.med_time_5').val('');
            $('.med_time_6').val('');
            $('.med_time_7').val('');
            $('.med_time_8').val('');
        }

    });

    function current_med_administer(action, id, prescription_id = '', is_prn = 0) {
        if (is_prn) {

            let serverUrl = "check_administered_warning.php?csrf_token_form=<?= $csrf_token; ?>&prescription_id=" + prescription_id;
            $.ajax({
                url: serverUrl,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    if (response[1] >= response[0])
                        alert('This Medication is administered more than instructed');

                    singleAdminister(id);
                }
            });

        } else {
            singleAdminister(id);
        }
        //return false;
        function singleAdminister(id) {

            if (action == 'confirm') {
                $('.singleadmin').val("true");
                if (!confirm('Are you sure you would like to administer this medicine?'))
                    return false;
            } else if (action == 'administed_late') {
                $('.late').val(1);
                $('.singleadmin').val("true");
                if ($("#staff_note_paste_" + id).val() == "") {
                    alert("Please fill the Note");
                    return false;
                }
            } else if (action == 'held') {
                $('.held').val(1);
                $('.singleadmin').val("true");
                if ($("#staff_note_paste_" + id).val() == "") {
                    alert("Please fill the Note");
                    return false;
                }
            } else if (action == 'missed') {
                $('.missed').val(1);
                $('.singleadmin').val("true");
                if ($("#staff_note_paste_" + id).val() == "") {
                    alert("Please fill the Note");
                    return false;
                }
            } else {
                $('.refused').val(1);
                $('.singleadmin').val("true");
                if ($("#staff_note_paste_" + id).val() == "") {
                    alert("Please fill the Note");
                    return false;
                }
            }

            let serverUrl = "administrative_popup.php?csrf_token_form=<?= $csrf_token; ?>";

            $.ajax({
                url: serverUrl,
                type: "POST",
                data: $('#admin_popup_' + id).serialize(),
                success: function(response) {
                    window.location.reload();
                    //console.log('updated' + response);
                }
            });
        }


        return true;
    }

    function changeLinkHref(id, addValue, val) {
        var myRegExp = new RegExp(val + ":");
        if (addValue) { //add value to href
            if (document.getElementById(id) !== null) document.getElementById(id).value += val + ':';
        } else { //remove value from href
            if (document.getElementById(id) !== null) document.getElementById(id).value = document.getElementById(id).value.replace(myRegExp, '');
        }
        console.log(document.getElementById(id).value);
        disable_btn_multimeds();
    }

    function disable_btn_multimeds() {
        var multimeds = $("#multimeds").val();
        var len = multimeds.length;
        if (multimeds === null || len === undefined || len === 0 || multimeds == '') {
            $(".btn_multimeds_administrate").prop("disabled", true);
            $(".btn_single_medlogs").prop("disabled", false);
        } else {
            $(".btn_multimeds_administrate").prop("disabled", false);
            $(".btn_single_medlogs").prop("disabled", true);
        }

    }

    function changeLinkHrefAll(addValue, value) {
        changeLinkHref('multimeds', addValue, value);
    }

    function changeLinkHref_All(id, addValue, val) {
        var myRegExp = new RegExp(val + ":");
        if (addValue) { //add value to href
            if (document.getElementById(id) !== null) document.getElementById(id).value += val + ':';
        } else { //remove value from href
            if (document.getElementById(id) !== null) document.getElementById(id).value = document.getElementById(id).value.replace(myRegExp, '');
        }
    }

    function Check() {
        var chk = document.getElementsByClassName('check_list');
        var len = chk.length;
        if (len == undefined) {
            chk.checked = true;
        } else {
            //clean the checked id's before check all the list again
            var multimeds = document.getElementById('multimeds');
            if (multimeds !== null) {

                multimeds.value = document.getElementById('multimeds').value.substring(0, document.getElementById('multimeds').value.indexOf('=') + 1);
            }


            for (pr = 0; pr < chk.length; pr++) {
                if ($(chk[pr]).parents("tr.inactive").length == 0) {
                    chk[pr].checked = true;
                    changeLinkHref_All('multimeds', true, chk[pr].value);
                }
            }
        }
        disable_btn_multimeds();
    }

    function Uncheck() {
        var chk = document.getElementsByClassName('check_list');
        var len = chk.length;
        if (len == undefined) {
            chk.checked = false;
        } else {
            for (pr = 0; pr < chk.length; pr++) {
                chk[pr].checked = false;
                changeLinkHref_All('multimeds', false, chk[pr].value);
            }
        }
        disable_btn_multimeds();
    }

    // function submitForm(){
    //     var form = document.getElementById("administrate_mdes_form");
    //     form.submit();
    // }

    var CheckForChecks = function(chk) {
        // Checks for any checked boxes, if none are found than an alert is raised and the link is killed
        if (Checking(chk) == false) {
            return false;
        }
        return top.restoreSession();
    };

    function Checking(chk) {
        var len = chk.length;
        var foundone = false;

        if (len == undefined) {
            if (chk.checked == true) {
                foundone = true;
            }
        } else {
            for (pr = 0; pr < chk.length; pr++) {
                if (chk[pr].checked == true) {
                    foundone = true;
                }
            }
        }
        if (foundone) {
            return true;
        } else {
            alert('Please select at least one med!');
            return false;
        }
    }

    $(function() {
        $(".check_list:checked").each(function() {
            changeLinkHref('multimeds', this.checked, this.value);
        });
        disable_btn_multimeds();
    });

    $(function() {
        $("#multimeds").on("click", function() {
            return CheckForChecks(document.check_list);
        });
    });

    // Get the modal
    var modal = document.getElementById("myModal");
    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];
    // When the user clicks the button, open the modal 
    // btn.onclick = function() {
    // 	modal.style.display = "block";
    // }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    $('.continue_meds').on('click', function(e) {
        //top.restoreSession();
        console.log('clicked' + $(this).attr('id'));
        let serverUrl = "medsbrougthin_prescribe.php?csrf_token_form=<?= $csrf_token; ?>";
        $.ajax({
            url: serverUrl,
            type: "POST",
            data: {
                "continue_meds": "true",
                'med_id': $(this).attr('id')
            },
            success: function(response) {
                //console.log('Result response : ' + response);

                //$(this).hide();
                e.preventDefault();
                window.location.reload();
            }
        });
    });
</script>

<!--<script type="text/javascript" src="custom_table_edit.js"></script> -->
<script>
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        });
    }
</script>

<!-- By Rudrayya 09-09-2022 Start -->
<script>
    $(function() {

        $('#sign_all_medo').on('click', function() {
            $grouplength = $('.insert_staff_signature:checkbox:checked').length;
            $grouplength2 = $('.insert_staff_signature_do:checkbox:checked').length;

            if ($grouplength > 0 && $grouplength2 > 0) {
                if ($('#my_sign_all:checkbox:checked').length > 0) {
                    if (confirm("Are you sure you want to approve all orders?")) {

                        // $i = 0;
                        $prescriptions_ids = [];
                        $do_ids = [];
                        $('.insert_staff_signature:checkbox:checked').each(function(i) {
                            var id = $(this).data('id');
                            $prescriptions_ids.push(id);
                        })

                        $('.insert_staff_signature_do:checkbox:checked').each(function(i) {
                            var id = $(this).data('id');
                            $do_ids.push(id);
                        })

                        var pid = document.getElementById('pid').value;
                        var user_id = document.getElementById('user_id').value;
                        let pi = $prescriptions_ids.join();
                        let di = $do_ids.join();

                        $.ajax({
                            url: base_url + "/eMAR/add_signature_to_all.php",
                            type: 'POST',
                            data: {
                                pid: pid,
                                user_id: user_id,
                                prescriptions_id: pi,
                                do_ids: di,
                            },
                            success: function(response) {
                                var response = $.parseJSON(response);
                                if (response.status) {
                                    $.ajax({
                                        url: base_url + "/eMAR/genrate_meds_pdf.php?prescriptions_ids=" + pi + "&header_id=" + response.id,
                                        success: function(response2) {
                                            location.reload();
                                        },
                                        error: function(err_msg) {
                                            console.log(err_msg);
                                        }
                                    });
                                    location.reload();
                                } else {
                                    alert("Some Thing Went Wrong Couldnt Sign the Medication");
                                }
                            }
                        });
                    }
                } else {
                    if (confirm("Are you sure you want to approve both orders?")) {

                        // $i = 0;
                        $prescriptions_ids = [];
                        $do_ids = [];
                        $('.insert_staff_signature:checkbox:checked').each(function(i) {
                            var id = $(this).data('id');
                            $prescriptions_ids.push(id);
                        })

                        $('.insert_staff_signature_do:checkbox:checked').each(function(i) {
                            var id = $(this).data('id');
                            $do_ids.push(id);
                        })

                        var pid = document.getElementById('pid').value;
                        var user_id = document.getElementById('user_id').value;
                        let pi = $prescriptions_ids.join();
                        let di = $do_ids.join();

                        $.ajax({
                            url: base_url + "/eMAR/add_signature_to_all.php",
                            type: 'POST',
                            data: {
                                pid: pid,
                                user_id: user_id,
                                prescriptions_id: pi,
                                do_ids: di,
                            },
                            success: function(response) {
                                var response = $.parseJSON(response);
                                if (response.status) {
                                    $.ajax({
                                        url: base_url + "/eMAR/genrate_meds_pdf.php?prescriptions_ids=" + pi + "&header_id=" + response.id,
                                        success: function(response2) {
                                            location.reload();
                                        },
                                        error: function(err_msg) {
                                            console.log(err_msg);
                                        }
                                    });
                                    location.reload();
                                } else {
                                    alert("Some Thing Went Wrong Couldnt Sign the Medication");
                                }
                            }
                        });
                    }
                }
            } else if ($grouplength > 0) {
                if (confirm("Do you wish to sign for these medications?")) {
                    // $grouplength = $('.insert_staff_signature:checkbox:checked').length;
                    // $i = 0;
                    $prescriptions_ids = [];
                    $('.insert_staff_signature:checkbox:checked').each(function(i) {
                        var id = $(this).data('id');
                        $prescriptions_ids.push(id);
                    })
                    var pid = document.getElementById('pid').value;
                    var user_id = document.getElementById('user_id').value;
                    let text = $prescriptions_ids.join();
                    $.ajax({
                        url: base_url + "/eMAR/add_signature.php",
                        type: 'POST',
                        data: {
                            pid: pid,
                            user_id: user_id,
                            prescriptions_id: text,
                        },
                        success: function(response) {
                            var response = $.parseJSON(response);
                            if (response.status) {
                                $.ajax({
                                    url: base_url + "/eMAR/genrate_meds_pdf.php?prescriptions_ids=" + text + "&header_id=" + response.id,
                                    success: function(response2) {
                                        location.reload();
                                    },
                                    error: function(err_msg) {
                                        console.log(err_msg);
                                    }
                                });
                            } else {
                                alert("Some Thing Went Wrong Couldnt Sign the Medication");
                            }
                        }
                    });
                }
            } else if ($grouplength2 > 0) {
                if (confirm("Do you wish to sign for these Doctors Orders?")) {
                    // $grouplength = $('.insert_staff_signature_do:checkbox:checked').length;
                    // $i = 0;
                    $do_ids = [];
                    $('.insert_staff_signature_do:checkbox:checked').each(function(i) {
                        var id = $(this).data('id');
                        $do_ids.push(id);
                    })
                    var pid = document.getElementById('pid').value;
                    var user_id = document.getElementById('user_id').value;
                    let text = $do_ids.join();
                    $.ajax({
                        url: base_url + "/eMAR/add_signature_do.php",
                        type: 'POST',
                        data: {
                            pid: pid,
                            user_id: user_id,
                            do_ids: text,
                        },
                        success: function(response) {
                            var response = $.parseJSON(response);
                            if (response.status) {
                                $.ajax({
                                    success: function(response2) {
                                        location.reload();
                                    },
                                    error: function(err_msg) {
                                        console.log(err_msg);
                                    }
                                });
                            } else {
                                alert("Some Thing Went Wrong Couldnt Sign the Doctors Orders");
                            }
                        }
                    });
                }
            } else {
                alert("No Medication and Doctors Order is Selected");
            }

        });

        // $('#staff_sign').on('click', function() {
        //     $grouplength = $('.insert_staff_signature:checkbox:checked').length;
        //     if ($grouplength > 0) {
        //         if (confirm("Do you wish to sign for these medications?")) {
        //             // $grouplength = $('.insert_staff_signature:checkbox:checked').length;
        //             // $i = 0;
        //             $prescriptions_ids = [];
        //             $('.insert_staff_signature:checkbox:checked').each(function(i) {
        //                 var id = $(this).data('id');
        //                 $prescriptions_ids.push(id);
        //             })
        //             var pid = document.getElementById('pid').value;
        //             var user_id = document.getElementById('user_id').value;
        //             let text = $prescriptions_ids.join();
        //             $.ajax({
        //                 url: base_url + "/eMAR/add_signature.php",
        //                 type: 'POST',
        //                 data: {
        //                     pid: pid,
        //                     user_id: user_id,
        //                     prescriptions_id: text,
        //                 },
        //                 success: function(response) {
        //                     var response = $.parseJSON(response);
        //                     if (response.status) {
        //                         $.ajax({
        //                             url: base_url + "/eMAR/genrate_meds_pdf.php?prescriptions_ids=" + text + "&header_id=" + response.id,
        //                             success: function(response2) {
        //                                 location.reload();
        //                             },
        //                             error: function(err_msg) {
        //                                 console.log(err_msg);
        //                             }
        //                         });
        //                     } else {
        //                         alert("Some Thing Went Wrong Couldnt Sign the Medication");
        //                     }
        //                 }
        //             });
        //         }
        //     }
        // });

        $('#other_staff_sign').on('click', function() {
            $grouplength = $('.insert_other_staff_signature:checkbox:checked').length;
            if ($grouplength > 0) {
                if (confirm("Do you wish to sign for these medications?")) {
                    $prescriptions_ids = [];
                    $('.insert_other_staff_signature:checkbox:checked').each(function(i) {
                        var id = $(this).data('id');
                        $prescriptions_ids.push(id);
                    })
                    var pid = document.getElementById('pid').value;
                    var user_id = document.getElementById('user_id').value;
                    let text = $prescriptions_ids.join();
                    $.ajax({
                        url: base_url + "/eMAR/add_signature.php",
                        type: 'POST',
                        data: {
                            pid: pid,
                            user_id: user_id,
                            prescriptions_id: text,
                        },
                        success: function(response) {
                            var response = $.parseJSON(response);
                            if (response.status) {
                                $.ajax({
                                    url: base_url + "/eMAR/genrate_meds_pdf.php?prescriptions_ids=" + text + "&header_id=" + response.id,
                                    success: function(response2) {
                                        location.reload();
                                    }
                                });
                            } else {
                                alert("Some Thing Went Wrong Couldnt Sign the Medication");
                            }
                        }
                    });
                }
            }
        });

        // $('#staff_sign_do').on('click', function() {
        //     $grouplength = $('.insert_staff_signature_do:checkbox:checked').length;
        //     if ($grouplength > 0) {
        //         if (confirm("Do you wish to sign for these Doctors Orders?")) {
        //             // $grouplength = $('.insert_staff_signature_do:checkbox:checked').length;
        //             // $i = 0;
        //             $do_ids = [];
        //             $('.insert_staff_signature_do:checkbox:checked').each(function(i) {
        //                 var id = $(this).data('id');
        //                 $do_ids.push(id);
        //             })
        //             var pid = document.getElementById('pid').value;
        //             var user_id = document.getElementById('user_id').value;
        //             let text = $do_ids.join();
        //             $.ajax({
        //                 url: base_url + "/eMAR/add_signature_do.php",
        //                 type: 'POST',
        //                 data: {
        //                     pid: pid,
        //                     user_id: user_id,
        //                     do_ids: text,
        //                 },
        //                 success: function(response) {
        //                     var response = $.parseJSON(response);
        //                     if (response.status) {
        //                         $.ajax({
        //                             success: function(response2) {
        //                                 location.reload();
        //                             },
        //                             error: function(err_msg) {
        //                                 console.log(err_msg);
        //                             }
        //                         });
        //                     } else {
        //                         alert("Some Thing Went Wrong Couldnt Sign the Doctors Orders");
        //                     }
        //                 }
        //             });
        //         }
        //     }
        // });

        $('#other_staff_sign_do').on('click', function() {
            $grouplength = $('.insert_other_staff_signature_do:checkbox:checked').length;
            if ($grouplength > 0) {
                if (confirm("Do you wish to sign for these Doctors Order?")) {
                    $do_ids = [];
                    $('.insert_other_staff_signature_do:checkbox:checked').each(function(i) {
                        var id = $(this).data('id');
                        $do_ids.push(id);
                    })
                    var pid = document.getElementById('pid').value;
                    var user_id = document.getElementById('user_id').value;
                    let text = $do_ids.join();
                    $.ajax({
                        url: base_url + "/eMAR/add_signature_do.php",
                        type: 'POST',
                        data: {
                            pid: pid,
                            user_id: user_id,
                            do_ids: text,
                        },
                        success: function(response) {
                            var response = $.parseJSON(response);
                            if (response.status) {
                                $.ajax({
                                    success: function(response2) {
                                        location.reload();
                                    }
                                });
                            } else {
                                alert("Some Thing Went Wrong Couldnt Sign the Doctors Orders");
                            }
                        }
                    });
                }
            }
        });
    });

    $(document).ready(function() {

        $(".insert_staff_signature").click(function() {
            if ($('.insert_staff_signature:checkbox:checked').length > 0) {
                document.getElementById('sign_all').style.display = "inline";
            } else {
                if ($('#my_sign_all:checkbox:checked').length > 0 || $('#my_select_all:checkbox:checked').length > 0) {

                } else {
                    document.getElementById('sign_all').style.display = "none";
                }
            }
        });

        $(".insert_other_staff_signature").click(function() {
            if ($('.insert_other_staff_signature:checkbox:checked').length > 0) {
                document.getElementById('other_signature_btn').style.display = "inline";
            } else {
                document.getElementById('other_signature_btn').style.display = "none";
            }
        });

        $(".insert_staff_signature_do").click(function() {
            if ($('.insert_staff_signature_do:checkbox:checked').length > 0) {
                document.getElementById('sign_all').style.display = "inline";
            } else {
                if ($('#my_sign_all:checkbox:checked').length > 0 || $('#my_select_all_do:checkbox:checked').length > 0) {

                } else {
                    document.getElementById('sign_all').style.display = "none";
                }
            }
        });

        $(".insert_other_staff_signature_do").click(function() {
            if ($('.insert_other_staff_signature_do:checkbox:checked').length > 0) {
                document.getElementById('other_signature_btn_do').style.display = "inline";
            } else {
                document.getElementById('other_signature_btn_do').style.display = "none";
            }
        });

        $("#my_select_all").click(function() {

            if ($('#my_select_all:checkbox:checked').length > 0) {
                document.getElementById('sign_all').style.display = "inline";
                $('.insert_staff_signature').prop('checked', true)
            } else {
                document.getElementById('sign_all').style.display = "none";
                $('.insert_staff_signature').prop('checked', false)
            }

        });

        $("#my_select_all_do").click(function() {

            if ($('#my_select_all_do:checkbox:checked').length > 0) {
                document.getElementById('sign_all').style.display = "inline";
                $('.insert_staff_signature_do').prop('checked', true)
            } else {
                document.getElementById('sign_all').style.display = "none";
                $('.insert_staff_signature_do').prop('checked', false)
            }

        });

        $("#other_select_all").click(function() {
            if ($('#other_select_all:checkbox:checked').length > 0) {
                document.getElementById('other_signature_btn').style.display = "inline";
                $('.insert_other_staff_signature').prop('checked', true)
            } else {
                document.getElementById('other_signature_btn').style.display = "none";
                $('.insert_other_staff_signature').prop('checked', false)
            }
        });

        $("#other_select_all_do").click(function() {
            if ($('#other_select_all_do:checkbox:checked').length > 0) {
                document.getElementById('other_signature_btn_do').style.display = "inline";
                $('.insert_other_staff_signature_do').prop('checked', true)
            } else {
                document.getElementById('other_signature_btn_do').style.display = "none";
                $('.insert_other_staff_signature_do').prop('checked', false)
            }
        });


        $("#my_sign_all").click(function() {
            if ($('#my_sign_all:checkbox:checked').length > 0) {
                document.getElementById('sign_all').style.display = "inline";
                $('.insert_staff_signature').prop('checked', true);
                $('.insert_staff_signature_do').prop('checked', true);
            } else {
                //all
                document.getElementById('sign_all').style.display = "none";
                $('.insert_staff_signature').prop('checked', false);
                $('.insert_staff_signature_do').prop('checked', false);
            }
        });

    });
</script>
<!-- By Rudrayya 09-09-2022 End -->

<script>
    $(function() {
        $('.datepicker').datetimepicker({
            timepicker: true,
            format: 'm/d/Y H:i'
        })
    });

    $(function() {
        $('.datepicker1').datetimepicker({
            timepicker: true,
            format: 'm-d-Y H:i:s'
        })
    });
</script>