<div class="modal fade" id="ordeset_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ordersets</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <input type="hidden" name="c" id="orderset_admin" value="<?= $orderset_admin ?>" />
                        <button type="button" class="btn btn-sm btn-primary" id="list_orderset">List Orderset</button>

                        <?php if ($orderset_admin) : ?>
                            <button type="button" class="btn btn-sm btn-primary" id="add_orderset">Add Orderset</button>
                        <?php endif; ?>
                    </div>
                    <div class="col-6" style="text-align: end;">
                        <!-- <a href="<?php //echo attr($url_webroot) 
                                        ?>/facesheet/print_orderset.php" target="_blank" class="btn btn-sm btn-primary">Print Orderset</a> -->
                    </div>
                </div>
                <section id="add_new_orderset_section" class="hideme mt-5">
                    <form name="add_new_orderset" id="add_new_orderset" method="">
                        <input type="hidden" name="event_name" value="add_new_orderset" />
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="orderset_name">Orderset Name</label>
                                    <input type="text" class="form-control" id="orderset_name" name="orderset_name" placeholder="Orderset Name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_special" id="flexCheckDefault">
                                    <label class="form-check-label mt-1" for="flexCheckDefault">
                                        Is Special
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn btn-primary" id="add_new_orderset_btn">Add</button>
                            </div>

                        </div>
                    </form>

                </section>
                <section id="list_orderset_section" class="mt-5">
                    <table class="table orderset_table" id="orderset_table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </section>
                <section id="single_orderset_section" class="hideme mt-1">

                </section>
                <section id="add_medication_section" class="hideme mt-5">
                    <form name="add_new_medication_form" id="add_new_medication_form" method="">
                        <input type="hidden" name="event_name" value="add_medication" />
                        <input type="hidden" name="orderset_id" id="orderset_id" value="" />

                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <div class="row">

                                    <div class="col-md-2">
                                        <label for="orderset_name">Drug</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="hidden" class="new_medication" id="drug_id" name="drug_id">
                                        <input type="text" class="form-control new_medication drug_name" id="drug_name" name="drug_name[]">
                                    </div>
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-9 position-relative">
                                        <div class="position-absolute hideme zindex-fixed bg-white" id="drug_name_list" style="z-index: 1500;">
                                            <ul class="list-group" id="drug_list" style="height: 300px; overflow-y:scroll;"></ul>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-12 mt-2">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="orderset_name">No of Days</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control new_medication no_of_days" name="quantity" id="quantity">
                                    </div>


                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="row">
                                    <div class="col-md-12 mt-2">
                                        <div class="row">

                                            <div class="col-md-2">
                                                <input type="hidden" name="ord_no[]" value="0" />
                                                <label for="orderset_name">Total Dose</label>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control new_medication" name="units[]">
                                            </div>
                                            <div class="col-md-2">
                                                <select select class="form-control new_medication" name="unit[]" id="unit">
                                                    <option label=" " value="0"> </option>
                                                    <option label="mg" value="1">mg</option>
                                                    <option label="mg/1cc" value="2">mg/1cc</option>
                                                    <option label="mg/2cc" value="3">mg/2cc</option>
                                                    <option label="mg/3cc" value="4">mg/3cc</option>
                                                    <option label="mg/4cc" value="5">mg/4cc</option>
                                                    <option label="mg/5cc" value="6">mg/5cc</option>
                                                    <option label="mcg" value="7">mcg</option>
                                                    <option label="grams" value="8">grams</option>
                                                    <option label="mL" value="9">mL</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <div class="row">


                                            <div class="col-md-2">
                                                <label for="orderset_name">Directions</label>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control new_medication" name="dasage[]">
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control new_medication" name="form[]" id="form">
                                                    <option label=" " value="0"> </option>
                                                    <option label="suspension" value="1">suspension</option>
                                                    <option label="tablet" value="2">tablet</option>
                                                    <option label="capsule" value="3">capsule</option>
                                                    <option label="solution" value="4">solution</option>
                                                    <option label="tsp" value="5">tsp</option>
                                                    <option label="ml" value="6">ml</option>
                                                    <option label="units" value="7">units</option>
                                                    <option label="inhalations" value="8">inhalations</option>
                                                    <option label="gtts(drops)" value="9">gtts(drops)</option>
                                                    <option label="cream" value="10">cream</option>
                                                    <option label="ointment" value="11">ointment</option>
                                                    <option label="puff" value="12">puff</option>
                                                    <option label="film" value="13">film</option>
                                                    <option label="lozenges" value="14">lozenges</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control new_medication" name="route[]" id="route">
                                                    <option label=" " value="0"> </option>
                                                    <option label="Oral" value="1">Oral</option>
                                                    <option label="Rectal" value="2">Rectal</option>
                                                    <option label="Topical" value="3">Topical</option>
                                                    <option label="Transdermal" value="4">Transdermal</option>
                                                    <option label="Sublingual" value="5">Sublingual</option>
                                                    <option label="Vaginal" value="6">Vaginal</option>
                                                    <option label="Percutaneous" value="7">Percutaneous</option>
                                                    <option label="Subcutaneous" value="8">Subcutaneous</option>
                                                    <option label="Intramuscular" value="9">Intramuscular</option>
                                                    <option label="Intra-arterial" value="10">Intra-arterial</option>
                                                    <option label="Intravenous" value="11">Intravenous</option>
                                                    <option label="Nasal" value="12">Nasal</option>
                                                    <option label="Other/Miscellaneous" value="other">Other/Miscellaneous</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control new_medication no_of_days interval" name="interval[]" id="interval">
                                                    <option data-val="0" label=" " value="0"> </option>
                                                    <option data-val="2" label="b.i.d. (Twice a day)" value="1">b.i.d. (Twice a day)</option>
                                                    <option data-val="3" label="t.i.d. (Thrice a day)" value="2">t.i.d. (Thrice a day)</option>
                                                    <option data-val="4" label="q.i.d. (Four times a day)" value="3">q.i.d. (Four times a day)</option>
                                                    <option data-val="8" label="q.3h (Every 3 Hours)" value="4">q.3.h (Every 3 Hours)</option>
                                                    <option data-val="6" label="q.4h (Every 4 Hours)" value="5">q.4.h (Every 4 Hours)</option>
                                                    <option data-val="5" label="q.5h (Every 5 Hours)" value="6">q.5.h (Every 5 Hours)</option>
                                                    <option data-val="4" label="q.6h (Every 6 Hours)" value="7">q.6.h (Every 6 Hours)</option>
                                                    <option data-val="3" label="q.8h (Every 8 Hours)" value="8">q.8.h (Every 8 Hours)</option>
                                                    <option data-val="1" label="q.d. (Once a day)" value="9">q.d. (Once a day)</option>

                                                    <option label="Stat /One Time Dose" value="18">Stat/One Time Dose</option>
                                                    <option label="p.r.n. (When necessary)" value="17">prn (When necessary)</option>
                                                    <option label="a.m. (Morning)" value="12">a.m. (Morning)</option>
                                                    <option label="p.m. (Evening)" value="13">p.m. (Evening)</option>
                                                    <option label="ante (In front of)" value="14">ante (In front of)</option>
                                                    <option value="19"> Every 30 Mins</option>
                                                    <option value="20"> Every 1 Hour</option>
                                                    <option value="21"> Every 2 Hour</option>

                                                </select>
                                            </div>
                                            <div class="col-md-2 pt-3">
                                                <input type="checkbox" name="is_prn[]" id="is_prn" class="is_prn" />
                                                <lable class='is_prn_lable'>Is PRN</lable>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="col-12 med_times_data">
                                <div class="row">
                                    <div class="col-md-4" id="space_adjust"></div>
                                    <div class="col-md-1 text-center px-0">
                                        <label class='med_time_label med_time_1' for="med_time_1">Time 1</label>
                                        <input size="5" class='med_time med_time_1' id='med_time_1' name='med_time[]' />
                                    </div>
                                    <div class="col-md-1 text-center px-0">
                                        <label class='med_time_label med_time_2' for="med_time_1">Time 2</label>
                                        <input size="5" class='med_time med_time_2' id='med_time_2' name='med_time[]' />
                                    </div>
                                    <div class="col-md-1 text-center px-0">
                                        <label class='med_time_label med_time_3' for="med_time_1">Time 3</label>
                                        <input size="5" class='med_time med_time_3' id='med_time_3' name='med_time[]' />
                                    </div>
                                    <div class="col-md-1 text-center px-0">
                                        <label class='med_time_label med_time_4' for="med_time_1">Time 4</label>
                                        <input size="5" class='med_time med_time_4' id='med_time_4' name='med_time[]' />
                                    </div>
                                    <div class="col-md-1 text-center px-0">
                                        <label class='med_time_label med_time_5' for="med_time_1">Time 5</label>
                                        <input size="5" class='med_time med_time_5' id='med_time_5' name='med_time[]' />
                                    </div>
                                    <div class="col-md-1 text-center px-0">
                                        <label class='med_time_label med_time_6' for="med_time_1">Time 6</label>
                                        <input size="5" class='med_time med_time_6' id='med_time_6' name='med_time[]' />
                                    </div>
                                    <div class="col-md-1 text-center px-0">
                                        <label class='med_time_label med_time_7' for="med_time_1">Time 7</label>
                                        <input size="5" class='med_time med_time_7' id='med_time_7' name='med_time[]' />
                                    </div>
                                    <div class="col-md-1 text-center px-0">
                                        <label class='med_time_label med_time_8' for="med_time_1">Time 8</label><br>
                                        <input size="5" class='med_time med_time_8' id='med_time_8' name='med_time[]' />
                                    </div>

                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row" id="orderset_main_area">


                                </div>
                            </div>


                            <div class="col-md-12 mt-2">
                                <hr class="my-3">
                                <div class="row">
                                    <div class="col-md-2 new_medication">
                                        <label for="orderset_name">Refills</label>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="refills" class="form-control new_medication">
                                            <option label="00" value="0" selected="selected">00</option>
                                            <option label="01" value="1">01</option>
                                            <option label="02" value="2">02</option>
                                            <option label="03" value="3">03</option>
                                            <option label="04" value="4">04</option>
                                            <option label="05" value="5">05</option>
                                            <option label="06" value="6">06</option>
                                            <option label="07" value="7">07</option>
                                            <option label="08" value="8">08</option>
                                            <option label="09" value="9">09</option>
                                            <option label="10" value="10">10</option>
                                            <option label="11" value="11">11</option>
                                            <option label="12" value="12">12</option>
                                            <option label="13" value="13">13</option>
                                            <option label="14" value="14">14</option>
                                            <option label="15" value="15">15</option>
                                            <option label="16" value="16">16</option>
                                            <option label="17" value="17">17</option>
                                            <option label="18" value="18">18</option>
                                            <option label="19" value="19">19</option>
                                            <option label="20" value="20">20</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        # of tablets:
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control new_medication" name="per_refill" id="per_refill">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="warning_txt">Warning</label>
                                    </div>
                                    <div class="col-md-10">
                                        <textarea class="form-control new_medication" size="35" type="text" name="warning_txt" id="warning_txt" value=""></textarea>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12 mt-2">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="orderset_name">Notes</label>
                                    </div>
                                    <div class="col-md-10">

                                        <textarea type="text" class="form-control new_medication" name="notes"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn btn-default px-3" id="add_new_medication_btn">Save</button>
                            </div>

                        </div>
                    </form>
                </section>

            </div>
            <div class="modal-footer">
                <span class="" id='msg_box'></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <div id="doctor_order_set" class="hideme">
                <div class="col-md-12 mt-2">
                    <div class="row">
                        <div class="col-3 mt-3">
                            <input type="hidden" name="set_type" data-type='docotr order' value="0" />
                            <label for="vital_check">Order</label>
                            <select class="form-control" name="vital_check" id='doctor_vital_check_orderset'>
                                <option value="Vital Check">Vital Check</option>
                                <option value="Check COWS">Check COWS</option>
                                <option value="Check CIWA Ar">Check CIWA Ar</option>
                                <option value="Check CIWA B">Check CIWA B</option>
                                <option value="Order CPAP machine">Order CPAP machine</option>
                                <option value="One to one supervision">One to one supervision</option>
                                <option value="Naloxone teaching to be done">Naloxone teaching to be done</option>
                                <option value="Narcan kit upon Discharge">Narcan kit upon Discharge</option>
                                <option value="Admit to ATS">Admit to ATS</option>
                                <option value="Admit to CSS">Admit to CSS</option>
                                <option value="other">Other</option>
                            </select>
                            <input class="form-control mt-2" value="" placeholder="Other Order" id='doctor_vital_check_other_orderset' name='doctor_vital_check_other' style="display: none;" />
                        </div>
                        <div class="col-3 mt-3">
                            <label for="vital_check">Frequency</label>
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
                        <div class="col-md-4 ps-0 mt-3">
                            <label for="note">Note:</label>
                            <textarea type="text" class="form-control" name="note"></textarea>
                        </div>
                        <div class="col-md-1 ps-0 pt-5">
                            <button type="button" class="btn btn-primary btn-sm save_doctors_order">Save</button>
                        </div>
                        <div class="col-md-1 ps-0 pt-5">
                            <button type="button" class="btn btn-primary btn-sm cancel_drug_medication">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="medicatioin_set" class="hideme">
                <div class="col-md-12 mt-2">
                    <div class="row">
                        <input type="hidden" name="set_type" data-type='medication' value="1" />
                        <div class="col-md-2">
                            <label for="orderset_name">Drug</label>
                        </div>
                        <div class="col-md-4">
                            <input type="hidden" name="drug_id" class="drug_id" value="0" />
                            <input type="text" class="form-control new_medication drugname" name="drug_name" value="" autocomplete="off">
                        </div>
                        <div class="col-md-1 offset-4 ps-0">
                            <button type="button" class="btn btn-primary btn-sm save_drug_medication">Save</button>
                        </div>
                        <div class="col-md-1 ps-0">
                            <button type="button" class="btn btn-primary btn-sm cancel_drug_medication">Cancel</button>
                        </div>
                        <div class="col-md-10 offset-md-2">
                            <div class="position-absolute hideme zindex-fixed bg-white drug_name_list" style="z-index: 1500;">
                                <ul class="list-group drug_list" style="height: 300px; overflow-y:scroll;"></ul>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-12 mt-2">
                    <div class="row">

                        <div class="col-md-2">
                            <label for="orderset_name">Total Dose</label>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control new_medication" name="units">
                        </div>
                        <div class="col-md-2">
                            <select select class="form-control new_medication" name="unit" id="unit">
                                <option label=" " value="0"> </option>
                                <option label="mg" value="1">mg</option>
                                <option label="mg/1cc" value="2">mg/1cc</option>
                                <option label="mg/2cc" value="3">mg/2cc</option>
                                <option label="mg/3cc" value="4">mg/3cc</option>
                                <option label="mg/4cc" value="5">mg/4cc</option>
                                <option label="mg/5cc" value="6">mg/5cc</option>
                                <option label="mcg" value="7">mcg</option>
                                <option label="grams" value="8">grams</option>
                                <option label="mL" value="9">mL</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-2">
                    <div class="row">


                        <div class="col-md-2">
                            <label for="orderset_name">Directions</label>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control new_medication" name="dosage">
                        </div>
                        <div class="col-md-2">
                            <select class="form-control new_medication" name="form" id="form">
                                <option label=" " value="0"> </option>
                                <option label="suspension" value="1">suspension</option>
                                <option label="tablet" value="2">tablet</option>
                                <option label="capsule" value="3">capsule</option>
                                <option label="solution" value="4">solution</option>
                                <option label="tsp" value="5">tsp</option>
                                <option label="ml" value="6">ml</option>
                                <option label="units" value="7">units</option>
                                <option label="inhalations" value="8">inhalations</option>
                                <option label="gtts(drops)" value="9">gtts(drops)</option>
                                <option label="cream" value="10">cream</option>
                                <option label="ointment" value="11">ointment</option>
                                <option label="puff" value="12">puff</option>
                                <option label="film" value="13">film</option>
                                <option label="lozenges" value="14">lozenges</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control new_medication" name="route" id="route">
                                <option label=" " value="0"> </option>
                                <option label="Oral" value="1">Oral</option>
                                <option label="Rectal" value="2">Rectal</option>
                                <option label="Topical" value="3">Topical</option>
                                <option label="Transdermal" value="4">Transdermal</option>
                                <option label="Sublingual" value="5">Sublingual</option>
                                <option label="Vaginal" value="6">Vaginal</option>
                                <option label="Percutaneous" value="7">Percutaneous</option>
                                <option label="Subcutaneous" value="8">Subcutaneous</option>
                                <option label="Intramuscular" value="9">Intramuscular</option>
                                <option label="Intra-arterial" value="10">Intra-arterial</option>
                                <option label="Intravenous" value="11">Intravenous</option>
                                <option label="Nasal" value="12">Nasal</option>
                                <option label="Other/Miscellaneous" value="other">Other/Miscellaneous</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select class="form-control new_medication interval" name="interval" id="interval">
                                <option data-val="2" label="b.i.d. (Twice a day)" value="1">b.i.d. (Twice a day)</option>
                                <option data-val="3" label="t.i.d. (Thrice a day)" value="2">t.i.d. (Thrice a day)</option>
                                <option data-val="4" label="q.i.d. (Four times a day)" value="3">q.i.d. (Four times a day)</option>
                                <option data-val="8" label="q.3h (Every 3 Hours)" value="4">q.3.h (Every 3 Hours)</option>
                                <option data-val="6" label="q.4h (Every 4 Hours)" value="5">q.4.h (Every 4 Hours)</option>
                                <option data-val="5" label="q.5h (Every 5 Hours)" value="6">q.5.h (Every 5 Hours)</option>
                                <option data-val="4" label="q.6h (Every 6 Hours)" value="7">q.6.h (Every 6 Hours)</option>
                                <option data-val="3" label="q.8h (Every 8 Hours)" value="8">q.8.h (Every 8 Hours)</option>
                                <option data-val="1" label="q.d. (Once a day)" value="9">q.d. (Once a day)</option>
                                <option label="Stat /One Time Dose" value="18">Stat /One Time Dose</option>
                                <option label="p.r.n. (When necessary)" value="17">prn (When necessary)</option>
                                <option label="a.c. (Before Meals)" value="10">a.c. (Before Meals)</option>
                                <option label="p.c. (After Meals)" value="11">pc. (After Meals)</option>
                                <option label="a.m. (Morning)" value="12">a.m. (Morning)</option>
                                <option label="p.m. (Evening)" value="13">p.m. (Evening)</option>
                                <option label="ante (In front of)" value="14">ante (In front of)</option>
                                <option value="19"> Every 30 Mins</option>
                                <option value="20"> Every 1 Hour</option>
                                <option value="21"> Every 2 Hour</option>
                            </select>
                        </div>
                        <div class="col-md-2 pt-3">
                            <input type="checkbox" name="is_prn" id="is_prn" class="is_prn" />
                            <lable class="is_prn_lable">Is PRN</lable>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-2 med_times_data">
                    <div class="row">
                        <div class="col-md-4" id="space_adjust"></div>
                        <div class="col-md-1 text-center px-0">
                            <label class='med_time_label med_time_1' for="med_time_1">Time 1</label>
                            <input size="5" type="text" class='form-control form-control-medtime med_time med_time_1' id='med_time_1' name='med_time[]' />
                        </div>
                        <div class="col-md-1 text-center px-0">
                            <label class='med_time_label med_time_2' for="med_time_1">Time 2</label>
                            <input size="5" type="text" class='form-control form-control-medtime med_time med_time_2' id='med_time_2' name='med_time[]' />
                        </div>
                        <div class="col-md-1 text-center px-0">
                            <label class='med_time_label med_time_3' for="med_time_1">Time 3</label>
                            <input size="5" type="text" class='form-control form-control-medtime med_time med_time_3' id='med_time_3' name='med_time[]' />
                        </div>
                        <div class="col-md-1 text-center px-0">
                            <label class='med_time_label med_time_4' for="med_time_1">Time 4</label>
                            <input size="5" type="text" class='form-control form-control-medtime med_time med_time_4' id='med_time_4' name='med_time[]' />
                        </div>
                        <div class="col-md-1 text-center px-0">
                            <label class='med_time_label med_time_5' for="med_time_1">Time 5</label>
                            <input size="5" type="text" class='form-control form-control-medtime med_time med_time_5' id='med_time_5' name='med_time[]' />
                        </div>
                        <div class="col-md-1 text-center px-0">
                            <label class='med_time_label med_time_6' for="med_time_1">Time 6</label>
                            <input size="5" type="text" class='form-control form-control-medtime med_time med_time_6' id='med_time_6' name='med_time[]' />
                        </div>
                        <div class="col-md-1 text-center px-0">
                            <label class='med_time_label med_time_7' for="med_time_1">Time 7</label>
                            <input size="5" type="text" class='form-control form-control-medtime med_time med_time_7' id='med_time_7' name='med_time[]' />
                        </div>
                        <div class="col-md-1 text-center px-0">
                            <label class='med_time_label med_time_8' for="med_time_1">Time 8</label><br>
                            <input size="5" type="text" class='form-control form-control-medtime med_time med_time_8' id='med_time_8' name='med_time[]' />
                        </div>

                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="warning_txt">Warning</label>
                        </div>
                        <div class="col-md-10">
                            <textarea class="form-control new_medication" size="35" type="text" name="warning_txt" id="warning_txt" value=""></textarea>
                        </div>
                    </div>
                </div>


                <div class="col-md-12 mt-2">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="orderset_name">Notes</label>
                        </div>
                        <div class="col-md-10">

                            <textarea type="text" class="form-control new_medication" name="notes"></textarea>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>


<script>
    let orderset_admin = <?= ($orderset_admin) ? 1 : 0; ?>;

    const vital_check_items = ["Vital Check", "Check COWS", "Check CIWA Ar", "Check CIWA B", "Order CPAP machine", "One to one supervision", "Naloxone teaching to be done", "Narcan kit upon Discharge", "Admit to ATS", "Admit to CSS", "other"];

    $('#ordeset_modal').on('show.bs.modal', function(event) {
        //   var button = $(event.relatedTarget) // Button that triggered the modal
        //   var recipient = button.data('whatever') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        $('#ordeset_modal').css('padding-top', '0');
        load_ordersets();
        $('.orderset_table').each(function() {
            console.log('converting');
            $('this').DataTable();
        });

        //   modal.find('.modal-title').text('New message to ' + recipient)
        //   modal.find('.modal-body input').val(recipient)
    });

    function hide_all_sections() {
        $('#add_new_orderset_section').hide();
        $('#list_orderset_section').hide();
        $('#single_orderset_section').hide();
        $('#add_medication_section').hide();
        console.log('hide all sections');

    }

    $('#list_orderset').on('click', function() {
        hide_all_sections();
        $('#list_orderset_section').show();
        load_ordersets(<?php $pid ?>);
        $('.orderset_table').each(function() {
            console.log('converting');
            $('this').DataTable();
        });

    });

    $('#add_orderset').on('click', function() {
        hide_all_sections();
        $('#add_new_orderset_section').show();
        console.log('add orderset');

    });



    function load_ordersets(pid = 0) {
        hide_all_sections();
        $.ajax({
            url: '/interface/eMAR/add_update_orderset.php',
            type: 'POST',
            data: {
                event_name: 'load_ordersets',
                pid: pid,
                orderset_admin_val: $('#orderset_admin').val(),
            },
            success: function(data) {
                $('#list_orderset_section').show();
                $('#list_orderset_section').html(data);
                orderset_table = $('#orderset_table tbody');
                $('.is_special_cls').each(function() {
                    if ($(this).val() == 1) {
                        $(this).prop('checked', true);
                    }
                });
            }
        });
    }

    function clear_fields() {
        $('.new_medication').each(function() {
            $(this).val('');
        });

        $('.med_time_label').hide();
        $('.med_time').hide();

    }

    function delete_orderset_day(orderset_id, day) {
        if (confirm('This action will delete the day & All the medications in that day,Do you wish to continue?')) {
            $.ajax({
                url: '/interface/eMAR/add_update_orderset.php',
                type: 'POST',
                data: {
                    event_name: 'delete_orderset_day',
                    orderset_id: orderset_id,
                    orderset_day: day,
                    orderset_admin_val: $('#orderset_admin').val(),

                },
                success: function(data) {
                    load_orderset(data, 'edit')
                }
            });
        }
    }

    function add_new_medication($id) {
        hide_all_sections();
        clear_fields();
        $('#orderset_main_area').empty();

        $("#add_medication_section").show();
        $('#orderset_id').val($id);
    }

    function load_orderset_day(day) {
        $.ajax({
            url: '/interface/eMAR/add_update_orderset.php',
            type: 'POST',
            dataType: "json",
            data: {
                event_name: 'load_orderset_day',
                event_type: 'edit',
                orderset_id: day[1],
                orderset_day: day[0],
                orderset_admin_val: $('#orderset_admin').val(),

            },
            success: function(data) {
                console.log(data);
                $('#orderset_day_' + data[0]).html(data[1]);
            }
        });
    }

    function load_orderset($id, event_type) {
        $('#orderset_id').val($id);
        hide_all_sections();
        $('#single_orderset_section').show();
        $.ajax({
            url: '/interface/eMAR/add_update_orderset.php',
            type: 'POST',
            data: {
                event_name: 'load_orderset',
                event_type: event_type,
                orderset_id: $id,
                orderset_admin_val: $('#orderset_admin').val(),

            },
            success: function(data) {
                $('#single_orderset_section').html(data);

                var acc = document.getElementsByClassName("accordion");
                var i;
                $('.datepicker').datetimepicker({
                    format: 'm/d/Y'
                });

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
                console.log('event :' + event_type);
                if (event_type == 'prescribe') {
                    $('#checkall').trigger('click');
                }
                // if(event_type == 'clone'){
                //     $('#orderset_id').val($id);
                // }
            }
        });
    }

    function clone_orderset($id, event_type) {
        $('#orderset_id').val($id);
        $.ajax({
            url: '/interface/eMAR/add_update_orderset.php',
            type: 'POST',
            data: {
                event_name: 'clone',
                event_type: event_type,
                orderset_id: $id,
            },
            success: function(data) {
                load_ordersets();
            }
        });
    }



    //add data to orderset
    $('#add_new_orderset_btn').on('click', function() {
        $.ajax({
            type: 'post',
            url: '/interface/eMAR/add_update_orderset.php',
            data: $('#add_new_orderset').serialize(),
            success: function(data) {
                //show list 
                console.log(data);
                load_ordersets(<?php echo $pid ?>);
            }
        });
    });

    $('#add_new_medication_btn').on('click', function() {
        $.ajax({
            type: 'post',
            url: '/interface/eMAR/add_update_orderset.php?pid=<?php $pid ?>',
            data: $('#add_new_medication_form').serialize(),
            success: function(data) {
                //show list 
                $('#orderset_main_area').empty();
                load_orderset($('#orderset_id').val());
            }
        });
    });

    $('body').on('click', '.save_doctors_order', function() {
        console.log('save_doctors_order');
        form_id = $(this).parents('form').attr('id');
        $.ajax({
            type: 'post',
            url: '/interface/eMAR/add_update_orderset.php',
            dataType: "json",
            data: $('#' + form_id).serialize(),
            success: function(data) {
                console.log('doctors order:');
                console.log(data);
                $('#orderset_day_' + data[0]).empty();
                load_orderset($('#orderset_id').val(), 'edit');
            },
            error: function(data) {
                console.log('error');
            }
        });
        $(this).parents('form').find('.add_edit_contrainer').empty();

    });

    $('body').on('click', '.save_drug_medication', function() {
        console.log('save_drug_medication');
        form_id = $(this).parents('form').attr('id');
        $.ajax({
            type: 'post',
            url: '/interface/eMAR/add_update_orderset.php',
            dataType: "json",
            data: $('#' + form_id).serialize(),
            success: function(data) {

                console.log(data['test']);
                load_orderset($('#orderset_id').val(), 'edit');


                /*
                if('add_medication_form-new' == form_id){
                    console.log(data);
                   load_orderset(data[1], 'edit');
                }else{
                    $('#orderset_day_' + data[0]).empty();
                    load_orderset_day(data);
                }*/
            },
            error: function(data) {
                console.log(data);
            }
        });
        $(this).parents('form').find('.add_edit_contrainer').empty();

    });

    $('body').on('click', '.cancel_drug_medication', function() {
        console.log('asd :' + $('#orderset_id').val());
        load_orderset($('#orderset_id').val(), 'edit');
        // $(this).parents('form').find('.add_edit_contrainer').empty();
        //$(this).parents('form').find('.add_edit_contrainer_new').empty();

    });

    $('body').on('click', '#add_day', function() {
        $('#new_day_container').show();
        $('#new_day_container').removeClass('d-none');
    });

    $('body').on('click', '.cancel_day', function() {
        $('#new_day_container').hide();
        $('#new_day_container').addClass('d-none');
    });



    $('body').on('click', '.btn_add_drug', function() {
        console.log('clicked add drug button');
        console.log($(this).data('day'));
        $(this).parent().find('.add_edit_contrainer').html($('#medicatioin_set').html());
        $(this).parent().find('[name="id"]').val('');
        $(this).parent().find('[name="set_type"]').val(1);
        $(this).parent().find('[name="day"]').val($(this).data('day'));

        $('.med_time').each(function() {
            $(this).datetimepicker({
                datepicker: false,
                format: 'H:i'
            });
        });
    });

    $('body').on('click', '.btn_add_doctor_order', function() {
        console.log($(this).data('day'));
        $(this).parent().find('.add_edit_contrainer').html($('#doctor_order_set').html());
        $(this).parent().find('[name="id"]').val('');
        $(this).parent().find('[name="set_type"]').val(0);
        $(this).parent().find('[name="day"]').val($(this).data('day'));

    });

    $('body').on('click', '#update_medication', function() {
        $.ajax({
            type: 'post',
            url: '/interface/eMAR/add_update_orderset.php',
            data: $('#edit_medication_form').serialize(),
            success: function(data) {
                console.log(data);
                //show list 
                setTimeout(function() {
                    window.location.reload();
                });
            }
        });
    });

    $('body').on('click', '#checkall', function() {
        //$('.orderset_item').not(this).prop('checked', this.checked);
        $('.orderset_item_day').not(this).prop('checked', this.checked);
        $('.orderset_item').not(this).prop('checked', this.checked);
    });

    $('body').on('click', '.orderset_item_day', function() {
        var day = $(this).data('val');
        $('.day_' + day).not(this).prop('checked', this.checked);
    });

    $('body').on('blur', '.update_orderset_name', function() {
        if ($('#orderset_admin').val()) {
            $.ajax({
                type: 'post',
                url: '/interface/eMAR/update_orderset.php',
                data: {
                    check_box: 0,
                    txtval: $(this).val(),
                    orderset_id: $(this).attr('id')
                },
                success: function(data) {
                    console.log(data);
                }
            });
        }

    });

    $('body').on('click', '.is_special_cls', function() {
        let check_val = ($(this).is(":checked")) ? 1 : 0;

        $.ajax({
            type: 'post',
            url: '/interface/eMAR/update_orderset.php',
            data: {
                check_box: 1,
                txtval: check_val,
                orderset_id: $(this).data('id')
            },
            success: function(data) {
                console.log(data);
            }
        });

        if ($(this).is(":checked")) {
            console.log('checked' + $(this).data('id'));
        }
    });

    $('body').on('click', '#prescribe_orderset_btn', function() {
        if (($('#approved_by_auo').val() != "" && $('#verbal_auo').val() != "") || orderset_admin) {
            if (confirm('You are prescribing the orderset (' + $(this).data('val') + '). Do you wish to continue?')) {
                $.ajax({
                    type: 'post',
                    url: '/interface/eMAR/add_update_orderset.php?pid=<?php echo $pid ?>',
                    data: $('#prescribe_medication_form').serialize(),
                    success: function(data) {
                        //show list 
                        $('#msg_box').addClass('alert alert-success');
                        $('#msg_box').text('Medication prescribed.');
                        setTimeout(function() {
                            window.location.reload();
                        });
                    }
                });
            }
        } else {
            alert('Please Input Approved By and Verbal');
        }
    });

    $('body').on('click', '.delete_medication', function() {
        medication_id = $(this).attr('id');
        console.log($(this).closest("tr").remove());
        $.ajax({
            url: '/interface/eMAR/add_update_orderset.php',
            type: 'POST',
            data: {
                event_name: 'delete_medication',
                medication_id: medication_id,
            },
            success: function(data) {
                $('#drug_' + data).remove();
                $('#hr_' + data).remove();
            }
        });
    });


    $('body').on('click', '.edit_medication', function() {
        medication_id = $(this).data('id');
        $this = $(this);
        $.ajax({
            url: '/interface/eMAR/add_update_orderset.php',
            type: 'POST',
            dataType: 'json',
            data: {
                event_name: 'load_medication',
                medication_id: medication_id,
            },
            success: function(data) {
                console.log(data);
                if (data['set_type'] == 1) {
                    $('#add_edit_contrainer_' + data['day']).html($('#medicatioin_set').html());
                    $('.med_time').each(function() {
                        $(this).datetimepicker({
                            datepicker: false,
                            format: 'H:i'
                        });
                    });
                    $this.parents().find('.drug_id').val(data['drug_id']);
                    $this.parents().find('[name="units"]').val(data['units']);
                    console.log('units :' + data['units']);
                    $this.parents().find('[name="notes"]').val(data['note']);
                    $this.parents().find('[name="warning_txt"]').val(data['warning_txt']);
                    $this.parents().find('#unit').val(data['unit']);
                    $this.parents().find('#form').val(data['form']);
                    $this.parents().find('#route').val(data['route']);
                    $this.parents().find('[name="dosage"]').val(data['dosage']);
                    $this.parents().find('.drugname').val(data['drug_name']);
                    $this.parents().find('#interval').val(data['intervals']);

                    if (data['is_prn'] == 1) {
                        $this.parents().find('#is_prn').prop('checked', true);
                    }

                    if (data['is_prn'] == 1 && data['intervals'] == 18) {
                        $this.parents().find('.med_times_data').addClass("d-none");
                    }

                    for (var i = 0; i < 8; i++) {
                        $this.parents().find('.med_time_' + (i + 1)).hide();
                    }

                    const myArray = data['med_time'].split(",");
                    for (var i = 0; i < myArray.length; i++) {
                        $this.parents().find('#med_time_' + (i + 1)).val(myArray[i]);
                        $this.parents().find('.med_time_' + (i + 1)).show();

                    }
                    $this.parents().find('.drugname').focus();

                } else {
                    $('#add_edit_contrainer_' + data['day']).html($('#doctor_order_set').html());
                    if (vital_check_items.includes(data['drug_name'])) {
                        $this.parents().find('[name="vital_check"]').val(data['drug_name']);
                    } else {
                        $this.parents().find('[name="vital_check"]').val("other");
                        $this.parents().find('[name="doctor_vital_check_other"]').val(data['drug_name']);
                        $this.parents().find('[name="doctor_vital_check_other"]').show();
                    }

                    $this.parents().find('[name="frequency"]').val(data['dosage']);
                    $this.parents().find('[name="note"]').val(data['note']);
                    $this.parents().find('[name="vital_check"]').focus();

                }

                console.log('#add_edit_contrainer_' + data['day']);

                $this.parents().find('[name="id"]').val(data['id']);
                $this.parents().find('[name="day"]').val(data['day']);
                $this.parents().find('[name="set_type"]').val(data['set_type']);


                //$('#single_orderset_section').html(data);
                //$('.interval1').trigger('change');

            }
        });
    });

    $("body").on('change', '#interval', function() {
        if ($('#interval').val() == 18 && $('#is_prn:checkbox:checked').length > 0) {
            $('.med_times_data').addClass("d-none");
        } else {
            $('.med_times_data').removeClass("d-none");
        }
    });

    $("body").on('change', '#is_prn', function() {
        if ($('#interval').val() == 18 && $('#is_prn:checkbox:checked').length > 0) {
            $('.med_times_data').addClass("d-none");
        } else {
            $('.med_times_data').removeClass("d-none");
        }
    });

    $('body').on('keypress', '.drugname', function() {
        var drug_name = $(this).val();
        var $t = $(this);
        $.ajax({
            url: '/library/ajax/drug_autocomplete/search_orderset.php?csrf_token_form=<?php echo $csrf_token ?>&term=' + drug_name,
            type: 'GET',
            success: function(data) {
                var json = JSON.parse(data);

                var $list = $t.parents().find('.drug_list');
                $list.empty();
                $t.parents().find('.drug_name_list').show();
                $t.parents().find('.drug_name_list').removeClass('d-none');
                // $('#drug_name_list').html(data);
                for (var i in json)
                    $list.append('<li class="list-group-item drug_item" id="' + i + '">' + json[i] + '</li>');

            }
        });
    });

    $(document).ready(function() {
        let interval_arr = [0, 2, 3, 4, 8, 6, 5, 4, 3, 1];

        $('body').on('change', '#doctor_vital_check_orderset', function() {
            if ($(this).val() == 'other') {
                $('#doctor_vital_check_other_orderset').show();
            } else {
                $('#doctor_vital_check_other_orderset').hide();
            }
        });

        $('body').on('click', '.drug_item', function() {
            $(this).parents().find('.drugname').val($(this).text());
            $(this).parents().find('.drug_id').val($(this).attr('id'));
            $(this).parents().find('.drug_name_list').hide();
        });

        $('body').on('click', '#is_prn', function() {
            if ($(this).is(":checked")) {
                $('#orderset_main_area').empty();
            }
        });

        $('body').on('change', '.no_of_days', function() {
            console.log('no of days changed');
            let total = 0;
            let days = 1;
            days = (Number.isInteger(parseInt($('#quantity').val()))) ? $('#quantity').val() : 1;
            interval = parseInt($('#interval').val());

            total = Number.isInteger((interval_arr[interval] * days)) ? (interval_arr[interval] * days) : 0;

            $('#per_refill').val(total);
            $('#orderset_main_area').empty();

            for (i = 0; i < (days - 1); i++) {
                $('#orderset_main_area').append('<div class="col-12"><hr><h5>Day ' + (i + 2) + ' </h5><input type="hidden" name="ord_no[]" value="' + (i + 1) + '"></div>');
                $('#orderset_main_area').append($('#medicatioin_set').html());
            }

            $('.drugname').each(function() {
                $(this).val($('#drug_name').val());
            });

            if ($('#is_prn').is(":checked")) {
                $('#orderset_main_area').empty();
                hideAllTimeBox();
                $('.med_time_1').show();
                $('.med_time_1').val('08:00');
                $('#quantity').val('1');
            }


        });

    });

    $('.interval').trigger('change');

    $('body').on('change', '.interval', function() {

        hideAllTimeBox();
        set_tiime_boxes($(this).val())

    });

    $('body').on('change', '.interval1', function() {
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
</script>