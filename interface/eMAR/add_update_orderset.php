<?php
// include_once("db_connect.php");
require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");
// require_once("../../../../sites/default/sqlconf.php");

use OpenEMR\Common\Csrf\CsrfUtils;
use OpenEMR\Core\Header;

//   $sql_query_current = "select * from orderset";
//   $res_current = sqlStatement($sql_query_current);
//     $row_current = sqlFetchArray($res_current);
//     print_r($row_current);

$intervals = [0 => '', 1  => 'b.i.d. (Twice a day)', 2  => 't.i.d. (Thrice a day)', 3  => 'q.i.d. (Four times a day)', 4  => 'q.3h (Every 3 Hours)', 5  => 'q.4h (Every 4 Hours)', 6  => 'q.5h (Every 5 Hours)', 7  => 'q.6h (Every 6 Hours)', 8  => 'q.8h (Every 8 Hours)', 9  => 'q.d. (Once a day)', 10 => 'a.c. (Before Meals)', 11 => 'p.c. (After Meals)', 12 => 'a.m. (Morning)', 13 => 'p.m. (Evening)', 14 => 'ante (In front of)', 17 => 'p.r.n. (When necessary)', 18 => 'Stat /One Time Dose', 19 => 'Every 30 Mins', 20 => 'Every 1 Hour', 21 => 'Every 2 hour'];

$route_data = [0 => '', 1 => 'Oral', 2 => 'Rectal', 3 => 'Topical', 4 => 'Transdermal', 5 => 'Sublingual', 6 => 'Vaginal', 7 => 'Percutaneous', 8 => 'Subcutaneous', 9 => 'Intramuscular', 10 => 'Intra-arterial', 11 => 'Intravenous', 12 => 'Nasal', 13 => 'Other/Miscellaneous'];

$medication_units = [1 => 'mg', 'mg/1cc', 'mg/2cc', 'mg/3cc', 'mg/4cc', 'mg/5cc', 'mcg', 'grams', 'mL'];
$forms_options = [1 => 'suspension', 'tablet', 'capsule', 'solution', 'tsp', 'ml', 'units', 'inhalations', 'gtts(drops)', 'cream', 'ointment', 'puff', 'film', 'lozenges'];

$routes = [1 => 'Oral', 'Rectal', 'Topical', 'Transdermal', 'Sublingual', 'Vaginal', 'Percutaneous', 'Subcutaneous', 'Intramuscular', 'Intra-arterial', 'Intravenous', 'Nasal', 'other' => 'Other/Miscellaneous'];

$user_id = $_SESSION['authUserID'];

if (isset($_REQUEST['event_name'])) {

    $event_name = $_REQUEST['event_name'];
    $orderset_name = $_REQUEST['orderset_name'];
    $is_special = (isset($_REQUEST['is_special'])) ? 1 : 0;

    if ($event_name == 'add_new_orderset') {
        $sql_qry = "INSERT INTO `orderset`(name, is_special, created_by, updated_by)  values ( '" . $orderset_name . "', '" . $is_special . "','" . $user_id . "', '" . $user_id . "')";
        if (sqlStatement($sql_qry)) {
            log_user_event('eMAR - Orderset', 'Inserted Into Orderset For :' . $_REQUEST['pid'], $_SESSION['authUserID']);
        } else {
            log_user_event('eMAR - Orderset', 'Failed to Insert into Orderset For :' . $_REQUEST['pid'], $_SESSION['authUserID']);
        }
        return true;
    }

    if ($event_name == 'prescribe_medication_form') {
        $prescribe_order_setid = $_REQUEST['prescribe_order_setid'];
        $pid = $_REQUEST['pid'];
        $ids = implode(",", $_REQUEST['medication_ids']);
        $t_end_date = '';
        $temp_date = explode('/', $_REQUEST['start_date']);
        if (strlen($temp_date[2]) == 4)
            $t_start_date = $temp_date[2] . '-' . $temp_date[0] . '-' . ($temp_date[1]);
        else
            $t_start_date = $_REQUEST['start_date'];

        if (isset($_REQUEST['end_date'])) {
            $temp_date1 = explode('/', $_REQUEST['end_date']);
            if (strlen($temp_date1[2]) == 4)
                $t_end_date = $temp_date1[2] . '-' . $temp_date1[0] . '-' . ($temp_date1[1]);
            else
                $t_end_date = $_REQUEST['end_date'];
        }

        $get_max_medication_day = "SELECT MAX(day) as max_day FROM orderset_medication WHERE id IN (" . $ids . ")";
        $res_max_day = sqlStatement($get_max_medication_day);
        $max_day_row = sqlFetchArray($res_max_day);
        $max_day = $max_day_row['max_day'];
        

        $qry = "UPDATE prescriptions SET medication_status=0 WHERE patient_id='".$pid."' and order_set = '".$prescribe_order_setid."'";

        $qry_result =sqlStatement($qry);

        $qry1 = "UPDATE doctors_order SET medication_status=0 WHERE pid='".$pid."' and order_set = '".$prescribe_order_setid."'";

        $qry_result1 =sqlStatement($qry1);

        $get_medication = "SELECT * FROM orderset_medication WHERE id IN (" . $ids . ")";
        $res_medication = sqlStatement($get_medication);



        while ($row_medication = sqlFetchArray($res_medication)) {

            $orderset = $row_medication['orderset_id'];
            $orderset_medication_id = $row_medication['id'];
            $sql_orderset = "SELECT * FROM orderset WHERE id='$orderset'";
            $ordersets = sqlStatement($sql_orderset);
            $ordersets_row = sqlFetchArray($ordersets);

            $is_special = $ordersets_row['is_special'];


            $temp_medtime = $row_medication['med_time'];
            $start_date = date('Y-m-d', strtotime($t_start_date . '+' . ($row_medication['day'] - 1) . ' day'));
            $end_date = date('Y-m-d', strtotime($t_start_date . '+' . ($row_medication['day'] - 1) . ' day'));
            if ($row_medication['is_prn'] == 1 || $row_medication['intervals'] == 17) {
                if ($is_special == 1) {
                    $end_date = $t_end_date;
                }
                //     else
                //         $end_date = date('Y-m-d', strtotime($t_start_date . '+' . ($max_day - 1) . ' day'));
            }

            if (isset($_REQUEST['end_date'])) {
                $date1 = strtotime($start_date);
                $date2 = strtotime($end_date);
                $diff = $date2 - $date1;

                $date_diff = round($diff / (60 * 60 * 24)) + 1;
            } else {
                $date_diff = 1;
            }



            // $sql_qry = '';
            if ($row_medication['set_type']) {

                $drug_id = $row_medication['drug_id'];
                $sql_rdc = "SELECT * FROM erx_weno_drugs WHERE drug_id='$drug_id'";
                $rdc = sqlStatement($sql_rdc);
                $rdc_row = sqlFetchArray($rdc);

               

                $sql_qry = "INSERT INTO prescriptions (patient_id, date_added, date_modified,  drug, drug_id,rxnorm_drugcode,form, dosage, size, unit, route,instruction, note, start_date, end_date, `Interval`, quantity, `warning_txt`, `verbal`, med_time, is_prn, provider_id, order_set,medication_status,updated_by, encounter,orderset_medication_id) VALUES(
                    '" . $pid . "', '" . date('Y-m-d H:i:s') . "', '" . date('Y-m-d H:i:s') . "',  '" . $row_medication['drug_name'] . "', '" . $row_medication['drug_id'] . "', '" . $rdc_row['rxcui_drug_coded'] . "', '" . $row_medication['form'] . "', '" . $row_medication['dosage'] . "', '" . $row_medication['units'] . "', '" . $row_medication['unit'] . "', '" . $row_medication['route'] . "','" . $row_medication['instruction'] . "', '" . $row_medication['note'] . "', '" . $start_date . "','" . $end_date . "', '" . $row_medication['intervals'] . "', '" . $date_diff . "', '" . $row_medication['warning_txt'] . "', '" .  $_REQUEST['verbal'] . "', '" .  $temp_medtime . "', '" .  $row_medication['is_prn'] . "', '" . $_REQUEST['approved_by'] . "', '" . $row_medication['orderset_id'] . "' ,1, '" . $_SESSION['authUserID'] . "', '" . $_SESSION['encounter'] . "','".$orderset_medication_id."')";
            } else {
                $sql_qry = "INSERT INTO doctors_order (pid, activity, vital_check, frequency, start_date,encounter,end_date,note,provider_id,verbal_order,from_orderset,medication_status,orderset_medication_id,order_set) VALUES(" . $pid . ", 1, '" . $row_medication['drug_name'] . "','" . $row_medication['dosage'] . "','" . $start_date . "','" . $_SESSION['encounter'] . "','" . $start_date . "','" . $row_medication['note'] . "','" . $_REQUEST['approved_by'] . "','" . $_REQUEST['verbal'] . "','1',1,'".$orderset_medication_id."','" . $row_medication['orderset_id'] . "' )";
            }

            if (sqlStatement($sql_qry)) {
                log_user_event('eMAR - Orderset', 'Inserted into Prescription For :' . $_REQUEST['pid'], $_SESSION['authUserID']);
            } else {
                log_user_event('eMAR - Orderset', 'Failed to Insert into Prescription For :' . $_REQUEST['pid'], $_SESSION['authUserID']);
            }

            if ($row_medication['set_type']) {
                $max_qry = "SELECT MAX(id) as max_id FROM prescriptions";
                $max_res = sqlStatement($max_qry);
                $max_row = sqlFetchArray($max_res);
                $max_id = $max_row['max_id'];
                addMeds($max_id);
            }
        }
    }

    if ($event_name == 'add_medication') {
        $orders = $_POST['ord_no'];
        $med_time =  implode(',', array_filter($_POST['med_time'][$key]));
        foreach ($orders as $key => $order) {
            $is_prn = isset($_POST['is_prn'][$key]) ? 1 : 0;
            $sql_qry = "INSERT INTO `orderset_medication` (`id`, `drug_name`, `orderset_id`, `drug_id`, `dosage`, `quantity`, `size`, `unit`,`units`, `route`, `intervals`, `form`, `refills`, `per_refill`, `note`, `substitute`, `ord_no`,  `warning_txt`, `med_time`, `is_prn`) 
            VALUES (NULL, '" . $_POST['drug_name'][$key] . "', '" . $_POST['orderset_id'] . "', '" . $_POST['drug_id'] . "', '" . $_POST['dosage'][$key] . "', '" . $_POST['quantity'] . "', '" . $_POST['unit'][$key] . "', 
            '" . $_POST['unit'][$key] . "','" . $_POST['units'][$key] . "', '" . $_POST['route'][$key] . "', '" . $_POST['interval'][$key] . "', '" . $_POST['form'][$key] . "', '" . $_POST['refills'] . "', '" . $_POST['per_refill'] . "', '" . $_POST['notes'] . "', '" . $_POST['orderset_id'] . "', '" . $_POST['ord_no'][$key] . "',  '" . $_POST['warning_txt'] . "', '" . $med_time . "', '" . $is_prn . "');";
            if (sqlStatement($sql_qry)) {
                log_user_event('eMAR - Orderset', 'Inserted into orderset_medication For :' . $_REQUEST['pid'], $_SESSION['authUserID']);
            } else {
                log_user_event('eMAR - Orderset', 'Failed to Insert into orderset_medication For :' . $_REQUEST['pid'], $_SESSION['authUserID']);
            }
        }

        return true;
    }

    if ($event_name == 'load_ordersets') {
        $pid = $_REQUEST['pid'];
        $sql_qry = "SELECT orderset.*, users.fname, users.lname FROM `orderset` join users on users.id = orderset.created_by";
        $res = sqlStatement($sql_qry);
        $data = '<table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
							<th>Special</th>
                            <th>Created By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';
        while ($row = sqlFetchArray($res)) {
            $id = $row['id'];
            $pid = $_SESSION['pid'];
            $encounter_id = $_SESSION['encounter'];
            $sql_dq = "SELECT COUNT(`order_set`) as count FROM `prescriptions` WHERE `order_set`='$id' AND `patient_id`='$pid' AND `encounter`='$encounter_id'";
            $res_dq = sqlStatement($sql_dq);
            $row_dq = sqlFetchArray($res_dq);

            $data .= '<tr>
                                    <td><input class="form-control update_orderset_name" value="' . $row['name'] . '" id="' . $row['id'] . '" /></td><td><div class="form-check">
                                    <input class="form-check-input is_special_cls" data-id="' . $row['id'] . '" type="checkbox" name="is_special" value="' . $row['is_special'] . '">
                                
                                </div></td>
                                    <td>' . $row['fname'] . " " . $row['lname'] . '</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" onclick="load_orderset(' . $row['id'] . ',' . "'edit'" . ')">Edit</button>

                                        <button class="btn btn-primary btn-sm mx-2" onclick="clone_orderset(' . $row['id'] . ',' . "'clone'" . ')">Clone</button>';

            if ($row_dq['count'] > 0) {
            //     $data .=        '<span class="btn btn-primary btn-sm mx-2">Prescribed</span>';
            $data .=        '<button class="btn btn-primary btn-sm mx-2" onclick="load_orderset(' . $row['id'] . ',' . "'prescribe'" . ')">Prescribe</button>';
            $data .=        '<a href="print_prescribed_orderset.php?orderset_id=' . $row['id'] . '" target="_blank" class="btn btn-primary btn-sm">Print Precribed</a>';

             } else {
                $data .=        '<button class="btn btn-primary btn-sm mx-2" onclick="load_orderset(' . $row['id'] . ',' . "'prescribe'" . ')">Prescribe</button>';
                $data .=        '<a href="print_orderset.php?orderset_id=' . $row['id'] . '" target="_blank" class="btn btn-primary btn-sm">PrintFull</a>';
            }
            $data .= '   </td>
                                    </tr>';
        }

        $data .= '</tbody></table>';

        echo $data;
    }

    if ($event_name == 'load_orderset_day') {
        $add_edit_btn = ($_REQUEST['event_type'] == 'edit') ? 1 : 0;
        $add_edit_btn = 1;
        $arr = [];
        $arr[] = $_POST['orderset_day'];
        $query = 'SELECT * FROM orderset_medication WHERE orderset_id=' . $_POST['orderset_id'] . ' AND day=' . $_POST['orderset_day'];

        $res = sqlStatement($query);
        $html = '';
        while ($row = sqlFetchArray($res)) {
            if ($row['drug_name'] == '') continue;


            $is_prn_class = ($row['intervals'] == 18) ? ' stat_does_med' : '';
            $is_prn_class = ($row['is_prn'] || $row['intervals'] == 17) ? 'cls_prn' : $is_prn_class;


            $html .= '<div class="row p-2 ' . $is_prn_class . '" id="drug_' . $row['id'] . '">';

            if (!$add_edit_btn) {
                $html .=  '<div class="mb-3 col-1 pt-1">
                        <div class="form-check">
                        <input class="form-check-input" type="checkbox" class="orderset_item"   name="medication_ids[]" value="' . $row['id'] . '">
                        </div>
                    </div>';
            }

            $html .=   '<div class="mb-3 col-4">
                  <h6>' . $row['drug_name'] . '</h6> 
              </div>';

            if ($row['set_type']) {
                $html .=   '<div class="mb-3 col-1">
              <p>' . $route_data[$row['route']] . '</p> 
              </div>';
                $html .=   '<div class="mb-3 col-1">
              <p>' . $row['units'] . $medication_units[$row['unit']] . '</p> 
              </div>';
                $html .=  '<div class="mb-3 col-2">
              <p>' . $intervals[$row['intervals']] . '</p> 
              </div>';
            } else {
                $html .=   '<div class="mb-3 col-4">
              <p>' . $row['dosage'] . '</p> 
              </div>';
            }
            $html .=   '<div class="mb-3 col-2">
              <p>' . $row['note'] . '</p> 
              </div>';
            if ($add_edit_btn) {

                $html .=   "<div class='mb-3 col-2 p-0'>
              <button type='button' class='btn btn-sm edit_medication' data-id='" . $row['id'] . "'><i class='fa fa-pencil'></i></button>
          <button type='button' class='btn btn-sm btn-danger delete_medication' id='" . $row['id'] . "'><i class='fa fa-times'></i></button>
              </div>";
            }


            //echo "<td> <input type='date' name='start_date[". $row['id'] ."]' value='". date('Y-m-d', strtotime($Date. ' + '.($count_drug[$row['day']] - 1) .' days'))."'></td>";


            $html .=   "</div>";
            $html .=  "<hr id='hr_" . $row['id'] . "'>";
        }

        $arr[] = $html;

        echo json_encode($arr);
    }

    if ($event_name == 'clone') {
        $is_clone = ($_REQUEST['event_type'] == 'clone') ? 1 : 0;
        //$is_clone = ($event_type == 'clone') ? 1 : 0;

        // $is_clone = 1;

        if ($is_clone) {
            $orderset_id = $_REQUEST['orderset_id'];

            $query = "SELECT * from orderset where id = '" . $orderset_id . "'";
            $rez = sqlStatement($query);

            while ($row = sqlFetchArray($rez)) {
                if (count($row) >= 1) {

                    $insert_query = "INSERT INTO orderset (`id`, `name`, `is_special`, `created_by`, `updated_by`, `created`, `updated`) 
                VALUES (NULL, '" . $row['name'] . '- Copy' . "', '" . $row['is_special'] . "', '" . $row['created_by'] . "',
                '" . $row['updated_by'] . "','" . $row['created'] . "','" . $row['updated'] . "')";
                    $insert_id = sqlInsert($insert_query);
                }
            }

            $query = "SELECT * FROM orderset_medication  where orderset_id =  '" . $orderset_id . "'";
            $res = sqlStatement($query);
            while ($row = sqlFetchArray($res)) {
                $insert_query = "INSERT INTO orderset_medication (`id`, `drug_name`, `orderset_id`, `ord_no`, `drug_id`, `dosage`, 
                    `quantity`, `size`, `unit`, `units`, `route`, `intervals`, `instruction`, `form`, `refills`, `per_refill`, `note`, 
                    `warning_txt`,   `substitute`, `created`, `updated`, `med_time`, `is_prn`, `set_type`, `day`)
                     VALUES(NULL,'" . $row['drug_name'] . "','" . $insert_id . "','" . $row['ord_no'] . "','" . $row['drug_id'] . "','" . $row['dosage'] . "',
                    '" . $row['quantity'] . "','" . $row['size'] . "','" . $row['unit'] . "','" . $row['units'] . "','" . $row['route'] . "',
                    '" . $row['intervals'] . "','" . $row['instruction'] . "','" . $row['form'] . "','" . $row['refills'] . "','" . $row['per_refill'] . "',
                    '" . $row['note'] . "','" . $row['warning_txt'] . "','" . $row['substitute'] . "','" . $row['created'] . "',
                    '" . $row['updated'] . "','" . $row['med_time'] . "','" . $row['is_prn'] . "','" . $row['set_type'] . "','" . $row['day'] . "')";
                $res1 = sqlStatement($insert_query);
            }
        }
    }

    if ($event_name == 'delete_orderset_day') {
        $sql = 'DELETE from orderset_medication WHERE orderset_id =' . $_POST['orderset_id'] . ' AND day="' . $_POST['orderset_day'] . '"';
        //sqlStatement($sql);

        if (sqlStatement($sql)) {
            log_user_event('eMAR - Orderset', 'Deleted orderset_medication For :' . $_REQUEST['pid'], $_SESSION['authUserID']);
        } else {
            log_user_event('eMAR - Orderset', 'Failed to Delete orderset_medication For :' . $_REQUEST['pid'], $_SESSION['authUserID']);
        }

        echo $_POST['orderset_id'];
    }

    if ($event_name == 'update_medication') {

        $day_to_create = isset($_POST['no_of_day']) ? $_POST['no_of_day'] : 0;
        $arr = [$_POST['day'], $_POST['orderset_id']];

        if ($day_to_create) {
            $sql = "SELECT max(day) as day from orderset_medication WHERE orderset_id =" . $_POST['orderset_id'];
            $res = sqlStatement($sql);
            $row = sqlFetchArray($res);
            $max_day = ($row['day']) ? $row['day'] : 0;

            for ($i = 1; $i <= $day_to_create; $i++) {
                $qry = "INSERT INTO `orderset_medication` (`id`, `drug_name`,`orderset_id`, `set_type`, `day`, `ord_no`, `drug_id`, `created`, `updated`, `is_prn`) VALUES (NULL,  '', '" . $_POST['orderset_id'] . "', 1, '" . ($max_day + $i) . "', '0', 0,  CURRENT_TIMESTAMP, CURRENT_TIMESTAMP,  0)";
                //sqlStatement($qry);

                if (sqlStatement($qry)) {
                    log_user_event('eMAR - $qry', 'Inserted into orderset_medication(1) For :' . $_REQUEST['pid'], $_SESSION['authUserID']);
                } else {
                    log_user_event('eMAR - Orderset', 'Failed to Insert into orderset_medication(1) For :' . $_REQUEST['pid'], $_SESSION['authUserID']);
                }
            }
        } else {


            $inteval_break = 0;

            switch ($_POST['interval']) {
                case '1':
                    $inteval_break = 2;
                    break;

                case '2':;
                case '8':
                    $inteval_break = 3;
                    break;
                case '7':;
                case '3':
                    $inteval_break = 4;
                    break;

                case '4':
                    $inteval_break = 8;
                    break;

                case '5':
                    $inteval_break = 6;
                    break;

                case '6':
                    $inteval_break = 5;
                    break;

                case '9':;
                default:
                    $inteval_break = 1;
                    break;
            }

            $count = 0;
            foreach ($_POST['med_time'] as $key => $val) {
                $_POST['med_time'][$key] = ($val == '') ? '00:00' : $val;
                if (++$count >= $inteval_break) {
                    break;
                }
            }

            $med_time =  implode(',', array_filter($_POST['med_time']));

            $prn = (isset($_POST['is_prn'])) ? 1 : 0;
            $sql_qry = '';
            if (!$_POST['id']) {
                $qry = '';
                if ($_POST['set_type']) {
                    $qry = "INSERT INTO `orderset_medication` (`id`, `drug_name`,`orderset_id`, `set_type`, `day`, `ord_no`, `drug_id`, `dosage`,  `unit`, `units`, `route`, `intervals`, `form`, `refills`, `per_refill`, `note`, `warning_txt`,`substitute`, `created`, `updated`, `med_time`, `is_prn`) VALUES (NULL,  '" . $_POST['drug_name'] . "', '" . $_POST['orderset_id'] . "', '" . $_POST['set_type'] . "', '" . $_POST['day'] . "', '0', '" . $_POST['drug_id'] . "', '" . $_POST['dosage'] . "', '" . $_POST['unit'] . "',  '" . $_POST['units'] . "', '" . $_POST['route'] . "', '" . $_POST['interval'] . "',  '" . $_POST['form'] . "', NULL,NULL, '" . $_POST['notes'] . "',  '" . $_POST['warning_txt'] . "', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '" . $med_time . "', '" . $prn . "')";
                } else {
                    $vital_check = $_POST['vital_check'];
                    if ($vital_check == 'other') {
                        $vital_check = $_POST['doctor_vital_check_other'];
                    }

                    $qry = "INSERT INTO `orderset_medication` (`id`, `drug_name`,`orderset_id`, `set_type`, `day`, `ord_no`,  `dosage`,`note`,`created`, `updated`) VALUES (NULL,  '" . $vital_check . "', '" . $_POST['orderset_id'] . "', '" . $_POST['set_type'] . "', '" . $_POST['day'] . "', '0',  '" . $_POST['frequency'] . "','" . $_POST['note'] . "',CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
                }


                //sqlStatement($qry);

                if (sqlStatement($qry)) {
                    log_user_event('eMAR - $qry', 'Inserted into orderset_medication(2) For :' . $_REQUEST['pid'], $_SESSION['authUserID']);
                } else {
                    log_user_event('eMAR - Orderset', 'Failed to Insert into orderset_medication(2) For :' . $_REQUEST['pid'], $_SESSION['authUserID']);
                }

                $arr[] = $qry;
            } else {
                $sql_qry = '';

                if ($_POST['set_type']) {
                    $sql_qry = 'UPDATE `orderset_medication` SET ';
                    $sql_qry .= ' drug_name = "' . $_POST['drug_name'] . '"';
                    $sql_qry .= ', dosage = "' . $_POST['dosage'] . '"';
                    $sql_qry .= ', drug_id = "' . $_POST['drug_id'] . '"';
                    $sql_qry .= ', units = "' . $_POST['units'] . '"';
                    $sql_qry .= ', unit = "' . $_POST['unit'] . '"';
                    $sql_qry .= ', form = "' . $_POST['form'] . '"';
                    $sql_qry .= ', route = "' . $_POST['route'] . '"';
                    $sql_qry .= ', intervals = "' . $_POST['interval'] . '"';
                    $sql_qry .= ', is_prn = "' . $prn . '"';
                    $sql_qry .= ', med_time = "' . $med_time . '"';
                    $sql_qry .= ', warning_txt = "' . str_replace('"', '""', $_POST['warning_txt']) . '"';
                    $sql_qry .= ', note = "' . str_replace('"', '""', $_POST['notes']) . '"';
                    $sql_qry .= ' WHERE id=' . $_POST['id'];
                } else {
                    $sql_qry = 'UPDATE `orderset_medication` SET ';

                    if ($_POST['vital_check'] == 'other') {
                        $sql_qry .= ' drug_name = "' . $_POST['doctor_vital_check_other'] . '",';
                    } else {
                        $sql_qry .= ' drug_name = "' . $_POST['vital_check'] . '",';
                    }
                    $sql_qry .= ' dosage = "' . $_POST['frequency'] . '",';
                    $sql_qry .= ' note = "' . $_POST['note'] . '"';
                    $sql_qry .= ' WHERE id=' . $_POST['id'];
                }

                //sqlStatement($sql_qry);
                //$arr['test'] = $sql_qry;

                if (sqlStatement($sql_qry)) {
                    log_user_event('eMAR - $qry', 'Inserted into orderset_medication(1) For :' . $_REQUEST['pid'], $_SESSION['authUserID']);
                } else {
                    log_user_event('eMAR - Orderset', 'Failed to Insert into orderset_medication(1) For :' . $_REQUEST['pid'], $_SESSION['authUserID']);
                }
            }
        }

        echo json_encode($arr);
    }

    if ($event_name == 'delete_medication') {
        $orderset_id = $_REQUEST['medication_id'];

        $sql_qry = "DELETE FROM `orderset_medication` WHERE id=" . $orderset_id;
        sqlStatement($sql_qry);
        echo $_REQUEST['medication_id'];
    }

    if ($event_name == 'load_medication') {
        $orderset_id = $_REQUEST['medication_id'];

        $sql_qry = "SELECT * FROM `orderset_medication` WHERE id=" . $orderset_id;
        $res = sqlStatement($sql_qry);
        $order_set = sqlFetchArray($res);
        echo json_encode($order_set);
        exit;

        echo '<form name="edit_medication_form" id="edit_medication_form" method="">
                        <input type="hidden" name="event_name" value="update_medication" />
                        <input type="text" name="id" value="' . $orderset_id . '" />
                        <div calss="row">';

        echo '<div class="col-md-12 mt-2">
                                <div class="row">

                                    <div class="col-md-2">
                                        <label for="orderset_name">Drug</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="hidden" class="new_medication drug_id" id="drug_id" name="drug_id" value="' . $order_set['drug_id'] . '">
                                        <input type="text" class="form-control new_medication drugname drug_name"  name="drug_name" value="' . $order_set['drug_name'] . '" autocomplete="off">
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
                            </div>';

        echo '<div class="col-md-12 mt-2">
        <div class="row">
        <div class="col-md-12 mt-2">
            <div class="row">

                <div class="col-md-2">
                    <input type="hidden" name="ord_no" value="' . $order_set['ord_no'] . '" />
                    <label for="orderset_name">Total Dose</label>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control new_medication" name="units" value="' . $order_set['units'] . '">
                </div>
                <div class="col-md-2">
                    <select select class="form-control new_medication" name="unit" id="unit">
                        <option label=" " value="0"> </option>';

        foreach ($medication_units as $key => $medication_unit) {
            $selected = ($order_set['unit'] == $key) ? 'selected' : '';
            echo '<option label="' . $medication_unit . '" value="' . $key . '" ' . $selected . '> ' . $medication_unit . '</option>';
        }

        echo  ' </select>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-2">
            <div class="row">


                <div class="col-md-2">
                    <label for="orderset_name">Directions</label>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control new_medication" name="dosage"  value="' . $order_set['dosage'] . '">
                </div>
                <div class="col-md-2">
                    <select class="form-control new_medication" name="form" id="form">
                        <option label=" " value="0"> </option>';
        foreach ($forms_options as $key => $option) {
            $selected = ($order_set['form'] == $key) ? 'selected' : '';
            echo '<option label="' . $option . '" value="' . $key . '" ' . $selected . '> ' . $option . '</option>';
        }
        echo '</select>
                </div>
                <div class="col-md-2">
                    <select class="form-control new_medication" name="route" id="route">
                        <option label=" " value="0"> </option>';

        foreach ($routes as $key => $route) {
            $selected = ($order_set['route'] == $key) ? 'selected' : '';
            echo '<option label="' . $route . '" value="' . $key . '" ' . $selected . '> ' . $route . '</option>';
        }

        echo '</select>
                </div>
                <div class="col-md-2">
                    <select class="form-control new_medication interval1" name="interval" id="interval">';
        foreach ($intervals as $key => $interval) {
            $selected = ($order_set['intervals'] == $key) ? 'selected' : '';
            echo '<option label="' . $interval . '" value="' . $key . '" ' . $selected . '> ' . $interval . '</option>';
        }


        echo '</select>
                </div>';

        $is_prn = ($order_set['is_prn'] == 1) ? 'checked' : '';

        echo '<div class="col-md-2 pt-3">
                    <input type="checkbox" name="is_prn" id="is_prn" ' . $is_prn . '  />
                    <lable class="is_prn_lable">Is PRN</lable>
                </div>
            </div>
        </div>

        </div>
        </div>';

        $times = explode(',', $order_set['med_time']);
        echo '<div class="col-12" id="med_times_data">
        <div class="row">
        <div class="col-md-4" id="space_adjust"></div>
        <div class="col-md-1 text-center">
            <label class="med_time_label med_time_1" for="med_time_1">Time 1</label>
            <input size="5" class="med_time med_time_1" id="med_time_1" name="med_time[]" value="' . $times[0] . '" />
        </div>
        <div class="col-md-1 text-center">
            <label class="med_time_label med_time_2" for="med_time_1">Time 2</label>
            <input size="5" class="med_time med_time_2" id="med_time_2" name="med_time[]"  value="' . $times[1] . '" />
        </div>
        <div class="col-md-1 text-center">
            <label class="med_time_label med_time_3" for="med_time_1">Time 3</label>
            <input size="5" class="med_time med_time_3" id="med_time_3" name="med_time[]" value="' . $times[2] . '"  />
        </div>
        <div class="col-md-1 text-center">
            <label class="med_time_label med_time_4" for="med_time_1">Time 4</label>
            <input size="5" class="med_time med_time_4" id="med_time_4" name="med_time[]" value="' . $times[3] . '"  />
        </div>
        <div class="col-md-1 text-center">
            <label class="med_time_label med_time_5" for="med_time_1">Time 5</label>
            <input size="5" class="med_time med_time_5" id="med_time_5" name="med_time[]" value="' . $times[4] . '"  />
        </div>
        <div class="col-md-1 text-center">
            <label class="med_time_label med_time_6" for="med_time_1">Time 6</label>
            <input size="5" class="med_time med_time_6" id="med_time_6" name="med_time[]" value="' . $times[5] . '"  />
        </div>
        <div class="col-md-1 text-center">
            <label class="med_time_label med_time_7" for="med_time_1">Time 7</label>
            <input size="5" class="med_time med_time_7" id="med_time_7" name="med_time[]" value="' . $times[6] . '"  />
        </div>
        <div class="col-md-1 text-center">
            <label class="med_time_label med_time_8" for="med_time_1">Time 8</label><br>
            <input size="5" class="med_time med_time_8" id="med_time_8" name="med_time[]" value="' . $times[7] . '"  />
        </div>

        </div>
        </div>';

        echo '<div class="col-md-12 mt-2">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="warning_txt">Warning</label>
                                    </div>
                                    <div class="col-md-10">
										<input class="form-control new_medication" size="35" type="text" name="warning_txt" id="warning_txt" value="' . $order_set['warning_txt'] . '">
                                    </div>
                                </div>
                            </div>
							

                            <div class="col-md-12 mt-2">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="orderset_name">Notes</label>
                                    </div>
                                    <div class="col-md-10">

                                        <input type="text" class="form-control new_medication" name="note" value="' . $order_set['note'] . '">
                                    </div>
                                </div>
                            </div>';

        echo '<div class="col-md-12 mt-2 text-right"><button type="button" class="btn btn-sm btn-default" id="update_medication" >Update</button></div>';


        echo '</div> </form>';
    }

    if ($event_name == 'load_orderset') {
        $orderset_id = $_REQUEST['orderset_id'];
        $add_edit_btn = ($_REQUEST['event_type'] == 'edit') ? 1 : 0;
        $orderset_admin = $_REQUEST['orderset_admin_val'];

        $sql_qry = "SELECT * FROM `orderset` WHERE id=" . $orderset_id . "";
        $res = sqlStatement($sql_qry);
        $order_set = sqlFetchArray($res);

        $sql_qry = "SELECT os.name, osm.* FROM orderset os join  orderset_medication osm on os.id = osm.orderset_id WHERE os.id=" . $orderset_id . " order by day asc";
        $res = sqlStatement($sql_qry);
        $medications = [];

        if ($orderset_admin) {

            echo "<div class='row'><div class='col-12'>";
            echo "<h5 class='text-center mb-0'>" . $order_set['name'] . " </h5>";

            echo "</div></div>";
        }

        echo '<form name="prescribe_medication_form" id="prescribe_medication_form" method="">
                        <input type="hidden" name="event_name" value="prescribe_medication_form" />
                        <div calss="row">';

        if ($add_edit_btn) {
            echo "<div class='form-floating col-1 mt-3'>
                    <button type='button' class='btn mt-2 btn-sm' id='add_day' > + Add Days</button>
                </div>
                <hr>";
        }

        echo '<form name="temp-form" id="temp-form" method="">';
        echo '</form>';

        echo '<div class="col-12 d-none" id="new_day_container">';
        echo '<form name="add_medication_form-new" id="add_medication_form-new" method="">';

        echo '<input type="hidden" name="event_name" value="update_medication" />
                      <input type="hidden" name="id" value="" />
                      
                      <input type="hidden" name="orderset_id" value="' . $order_set['id'] . '" />';

        echo '<div id="add_edit_contrainer_new" class="my-3">';

        echo '<div class="col-md-12"><div class="row"><div class="col-2"><label for="orderset_name">No of Days to Add</label></div>

                <div class="col-2"><input type="number" class="form-control" name="no_of_day" value="" /></div>
                <div class="col-1"><button type="button" class="btn btn-primary btn-sm save_drug_medication" >Save</button></div>
                <div class="col-7"><button type="button" class="btn btn-primary btn-sm cancel_day" >Cancel</button></div>
                </div></div>';

        echo '</div>';
        echo '</form></div>';
        //Show it for presciption  
        if (!$add_edit_btn) {

            echo '<div class="row mb-3">';
            echo '<div class="col-2  pl-3 pt-3"> <h6>Start Date</h6></div>';
            echo '<div class="col-3 "><input type="text" name="start_date" class="form-control datepicker" id="orderset_start_date" value="' . date('m/d/Y') . '" /></div>';
            if ($order_set['is_special']) {
                echo '<div class="col-2  pl-3 pt-3"> <h6>End Date</h6></div>';
                echo '<div class="col-3 "><input type="text" name="end_date" class="form-control datepicker" id="orderset_end_date" value="' . date('m/d/Y') . '" /></div>';
                echo '<div class="col-2 "></div>';
            } else {
                echo '<div class="col-6 "></div>';
            }

            echo '<div class="col-12 "><div class="form-check ml-3">
                <input class="form-check-input " id="checkall" type="checkbox">
                <label for="orderset_name" class="pt-1 ml-2">Select All</label>
            </div></div>';
            echo '</div>';
        }

        echo "<div id='accordian_panel'>";

        $Date = date('m/d/Y');
        $count = 0;
        $count_drug = [];
        while ($row = sqlFetchArray($res)) {
            $count++;
            if (isset($count_drug[$row['day']])) {
                $count_drug[$row['day']]++;
            } else {
                if ($count != 1) {

                    echo '</div></div><!-- end of day-->';
                }

                $count_drug[$row['day']] = 1;
                echo '<button type="button" class="accordion">';
                if ($add_edit_btn) {
                    echo "<span class='p-3'><i class='fa fa-times' onclick='delete_orderset_day(" . $row['orderset_id'] . "," . $row['day'] . ")'></i></span>";
                } else {
                    echo '<div class="form-check">
                        <input class="form-check-input orderset_item_day"   type="checkbox" data-val="' . $row['day'] . '">
                        </div>';
                }

                echo '<span class="pl-4">Day ' . $row['day'] . '</span></button>
                      <div class="panel">';

                if ($add_edit_btn) {
                    echo '<button type="button" data-day="' . $row['day'] . '" class="btn btn-primary btn-sm mt-2 btn_add_drug">Add Med</button>
                      <button type="button" data-day="' . $row['day'] . '" class="btn btn-secondary btn-sm mt-2 btn_add_doctor_order">Add Doctor' . "'" . 's Order</button>';
                }

                echo '<div class="col-12">';
                echo '<form name="add_medication_form-' . ($row['day'] + 0) . '" id="add_medication_form-' . ($row['day'] + 0) . '" method="">';

                echo '<input type="hidden" name="event_name" value="update_medication" />
                      <input type="hidden" name="id" value="" />
                      <input type="hidden" name="day" value="' . ($row['day'] + 0) . '" />
                      <input type="hidden" name="orderset_id" value="' . $row['orderset_id'] . '" />';

                echo '<div class="add_edit_contrainer my-3" id="add_edit_contrainer_' . $row['day'] . '">';

                echo '</div>';
                echo '</form></div>';
                echo '<div id="orderset_day_' . ($row['day'] + 0) . '">';
            }

            if ($row['drug_name'] != '') {
                $is_prn_class = ($row['intervals'] == 18) ? ' stat_does_med' : '';
                $is_prn_class = ($row['is_prn'] || $row['intervals'] == 17) ? 'cls_prn' : $is_prn_class;
                echo '<div class="row p-2 ' . $is_prn_class . '" id="drug_' . $row['id'] . '">';

                if (!$add_edit_btn) {
                    echo '<div class="mb-3 col-1 pt-1">
                            <div class="form-check">
                            <input class="form-check-input orderset_item day_' . $row['day'] . '" type="checkbox" name="medication_ids[]" value="' . $row['id'] . '">
                            </div>
                        </div>';
                }

                echo '<div class="mb-3 col-4">
                            <h6>' . $row['drug_name'] . '</h6> 
                        </div>';

                if ($row['set_type']) {
                    echo '<div class="mb-3 col-1">
                        <p>' . $route_data[$row['route']] . '</p> 
                        </div>';
                    echo '<div class="mb-3 col-1">
                        <p>' . $row['units'] . $medication_units[$row['unit']] . '</p> 
                        </div>';
                    echo '<div class="mb-3 col-2">
                        <p>' . $intervals[$row['intervals']] . '</p> 
                        </div>';
                } else {
                    echo '<div class="mb-3 col-4">
                        <p>' . $row['dosage'] . '</p> 
                        </div>';
                }
                echo '<div class="mb-3 col-2">
                        <p>' . $row['note'] . '</p> 
                        </div>';


                if ($add_edit_btn) {
                    echo "<div class='mb-3 col-2 p-0 text-right'>
                    <button type='button' class='btn btn-sm edit_medication' data-id='" . $row['id'] . "'><i class='fa fa-pencil'></i></button>
                    <button type='button' class='btn btn-sm btn-danger delete_medication' id='" . $row['id'] . "'><i class='fa fa-times'></i></button>
                    </div>";
                }

                echo  "</div>";
                echo "<hr id='hr_" . $row['id'] . "'>";
            }
        }
        echo "</div></div>";

        if (!$add_edit_btn) {
            $flora_s = "";
            $nikol_s = "";
            $display_n = "";
            $required = "";

            if ($_SESSION['authUserID'] == 77) {
                $flora_s = "selected";
                $display_n = 'style="display:none"';
            } elseif ($_SESSION['authUserID'] == 141) {
                $nikol_s = "selected";
                $display_n = 'style="display:none"';
            } else {
                $required = "required";
            }

            echo '<div class="row mt-3">
            <div class="col-2">
        		<button type="button" class="btn btn-primary btn-sm" data-val="' . ucfirst($order_set['name']) . '" id="prescribe_orderset_btn">Prescribe</button>
                <input type="hidden" name="prescribe_order_setid" id="prescribe_order_setid" value="'. $order_set['id'] .'" >
            </div> 
            <div class="col-2">  
                

                <input type="button" class="btn btn-primary btn-sm" onClick="getCheckboxvalue(' . $order_set['id'] . ');" value="Print">
                
            </div>
			<div class="col-3" ' . $display_n . '>
				Approved By
				<select name="approved_by" id="approved_by_auo" class="form-control" ' . $required . '>
                    <option value="">Please Select Approved By</option>
					<option value="77" ' . $flora_s . '>Flora Sadri-Azarbayejani, DO</option>
					<option value="141" ' . $nikol_s . '>Nicole White</option>
				</select>
            </div>
			<div class="col-3" ' . $display_n . '>
				Verbal
				<select name="verbal" id="verbal_auo" class="form-control" ' . $required . '>
					<option value="" ' . $flora_s . ' ' . $nikol_s . '>Please Select Verbal</option>
					<option value="by_phone">By Phone</option>
					<option value="in_person">In Person</option>
				</select>
            </div>
			<div class="col-2">
            </div>
            </div>';
        }

        echo '
        </div>
        </form>';
    }
}

if ($user_id == "By Rudrayya") {

    // <!-- <script>
    //     function load_medication_id() {
    //         var medication_id = [];
    //         var med_id = '';
    //         medication_id = $("input[name='medication_ids[]']:checked").map(function() {
    //             return $(this).val();
    //         }).get();


    //         var orderset_id = $("input[name='orderset_id']").val();
    //         if (medication_id.lenght > 0) {

    //             med_id = medication_id.join(',');
    //             // let a= document.createElement('a');
    //             // a.target= '_blank';
    //             // a.href= 'print_checked_orderset.php?orderset_id='+orderset_id+'&medication_id='+med_id;
    //             // a.click();
    //             // window.location.href('print_checked_orderset.php?orderset_id='+orderset_id+'&medication_id='+med_id,'_blank');
    //         }
    //         return med_id;
    //     }



    //     function getCheckboxvalue(oderset_id) {
    //         var msg2 = [];
    //         var orderid = oderset_id;
    //         var start_date = '';
    //         var end_date = '';
    //         var approved_by = '';
    //         var varbal = '';

    //         if (document.getElementById("orderset_start_date").value != '') {
    //             start_date = document.getElementById("orderset_start_date").value;
    //         }

    //         if (document.getElementById("orderset_end_date") != null) {
    //             end_date = document.getElementById("orderset_end_date").value;
    //         }

    //         if (document.getElementById("approved_by_auo") != null) {
    //             approved_by = document.getElementById("approved_by_auo").value;
    //         }

    //         if (document.getElementById("verbal_auo") != null) {
    //             varbal = document.getElementById("verbal_auo").value;
    //         }

    //         console.log(document.getElementById("orderset_start_date").value);


    //         //orderset_id=document.getElementsByName("orderset_id");

    //         //msg1=document.getElementsByName("oderset_print_id");


    //         for (var i = 0; i < document.getElementsByName("medication_ids[]").length; i++) {
    //             if (document.getElementsByName("medication_ids[]")[i].checked) {
    //                 msg2.push(document.getElementsByName("medication_ids[]")[i].value);
    //             }
    //         }

    //         //   document.getElementsByClassName("gettext")[0].innerHTML = msg2.toString();

    //         let a = document.createElement('a');
    //         a.target = '_blank';
    //         a.href = 'print_checked_orderset.php?orderset_id=' + orderid + '&medication_id=' + msg2 + '&start_date=' + start_date + '&end_date=' + end_date + '&approved_by=' + approved_by + '&varbal=' + varbal;
    //         a.click();
    //     }
    // </script> -->
}

?>

