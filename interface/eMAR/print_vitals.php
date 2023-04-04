<?php

/**
 * Edit demographics.
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Brady Miller <brady.g.miller@gmail.com>
 * @copyright Copyright (c) 2017 Brady Miller <brady.g.miller@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

use Mpdf\Mpdf;

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/erx_javascript.inc.php");
require_once("$srcdir/validation/LBF_Validation.php");
require_once("$srcdir/patientvalidation.inc.php");
require_once("$srcdir/pid.inc");
require_once("$srcdir/patient.inc");

$pid = $_SESSION['pid'];
$encounter= $_SESSION['encounter'];

$query_pid = "SELECT * FROM `patient_data` WHERE `pid`='$pid'";
$res_pid = sqlStatement($query_pid);
$patient_details = sqlFetchArray($res_pid);

$sql = "SELECT patient_data.pid,patient_data.fname,patient_data.mname,patient_data.lname,patient_data.DOB,patient_data.sex,patient_data.admission_date,patient_data.lvl_care,patient_data.facility_id,facility.name,insurance_data.provider,insurance_data.plan_name,insurance_data.policy_number,insurance_data.group_number  FROM patient_data LEFT JOIN insurance_data ON patient_data.pid=insurance_data.pid JOIN facility ON facility.id=patient_data.facility_id WHERE patient_data.pid='$pid'";
$res_pd = sqlStatement($sql);
$rez = sqlFetchArray($res_pd);
// $rez = sqlQuery($sql, array($pid));
$provider = $rez['provider'];
echo $provider;
$sql2 = "SELECT insurance_companies.name FROM insurance_companies WHERE insurance_companies.id='$provider'";
$res_pd2 = sqlStatement($sql2);
$rez2 = sqlFetchArray($res_pd2);

$facility_id = $rez['facility_id'];
$query_facility = "SELECT * FROM `facility` WHERE id='$facility_id'";
$res_facility = sqlStatement($query_facility);
$facility_details = sqlFetchArray($res_facility);

$admission_date = ($rez['admission_date'] != '') ? date('m-d-Y', strtotime($rez['admission_date'])) : '';

ob_clean();
header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="mPdf"');
header('Content-Transfer-Encoding: binary');
header('Accept-Ranges: bytes');

$html = '<table width="100%" style="font-family: calibri;">
            <tr>
                <th>
                    <img class="logo oe-pull-toward" alt="openEMR small logo" style="width:auto;height:75px" border="0" src="' . $GLOBALS['images_static_relative'] . '/logo-full-con.png">
                    <br>
                    <span>Vitals Of ' . $patient_details['fname'] . ' ' . $patient_details['lname'] . '</span>
                </th>
                        
                <th style="text-align: left;">
                    <div class="row">
                        <div class="col-md-12" style="font-weight: bold;font-size: 12px;">
                            Psyclarity Health Massachusetts
                        </div>

                        <div class="col-md-12" style="font-weight: normal;font-size: 12px;">
                            163 Hamilton St
                        </div>
                                
                        <div class="col-md-12" style="font-weight: normal;font-size: 12px;">
                            Saugus,MA 01906
                        </div>
                                
                        <div class="col-md-12" style="font-weight: normal;font-size: 12px;">
                            United States
                        </div>
                        
                        <div class="col-md-12" style="font-weight: normal;font-size: 12px;">
                            (855) 920-5310
                        </div>
                    </div>
                </th>
            </tr>
        </table>
        <hr>';


$html .= '<table width="100%" style="font-family: calibri;">';
$html .= '<thead>
            <tr>
                <th style="border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;">Date and time of observation</th>
                <th style="border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;">Weight [lbs]</th>
                <th style="border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;">Height</th>
                <th style="border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;">Bps</th>
                <th style="border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;">Bpd</th>
                <th style="border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;">Temperature</th>
                <th style="border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;">Pulse</th>
                <th style="border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;">Respiration</th>
                <th style="border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;">O2 Saturation</th>
                <th style="border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;">BMI</th>
            </tr>
        </thead>';
$html .= '<tbody>';

$sql_query = "SELECT form_vitals.id,form_vitals.pid,form_vitals.bps,form_vitals.date,form_vitals.bpd,form_vitals.date_time,form_vitals.weight,form_vitals.height,temperature,form_vitals.temp_method,form_vitals.pulse,form_vitals.respiration,form_vitals.note,form_vitals.BMI,form_vitals.BMI_status,form_vitals.waist_circ,form_vitals.head_circ,form_vitals.oxygen_saturation FROM `form_vitals` JOIN `forms` ON forms.form_id=form_vitals.id WHERE form_vitals.pid = '" . $pid . "' AND forms.encounter='".$encounter."' order by date ASC";
$res = sqlStatement($sql_query);
while ($vitals_result = sqlFetchArray($res)) {
    if ($vitals_result['bps'] <= 80 || $vitals_result['bps'] >= 160) {
        $style = "style='border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;color:red'";
    } else {
        $style = "style='border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;'";
    }

    if ($vitals_result['bpd'] <= 60 || $vitals_result['bpd'] >= 100) {
        $style2 = "style='border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;color:red'";
    } else {
        $style2 = "style='border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;'";
    }

    if ($vitals_result['temperature'] <= 96.1 || $vitals_result['temperature'] >= 100.4) {
        $style3 = "style='border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;color:red'";
    } else {
        $style3 = "style='border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;'";
    }

    if ($vitals_result['pulse'] <= 60 || $vitals_result['pulse'] >= 120) {
        $style4 = "style='border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;color:red'";
    } else {
        $style4 = "style='border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;'";
    }

    if ($vitals_result['respiration'] <= 10 || $vitals_result['respiration'] >= 18) {
        $style5 = "style='border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;color:red'";
    } else {
        $style5 = "style='border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;'";
    }

    if ($vitals_result['oxygen_saturation'] <= 95 || $vitals_result['oxygen_saturation'] >= 101) {
        $style6 = "style='border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;color:red'";
    } else {
        $style6 = "style='border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;'";
    }

    $html .= '<tr>
                <td style="border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;">' . date("m-d-Y H:i:s", strtotime($vitals_result['date_time'])) . '</td>
                <td style="border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;">' . $vitals_result['weight'] . '</td>
                <td style="border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;">' . $vitals_result['height'] . '</td>
                <td ' . $style . '>' . $vitals_result['bps'] . '</td>
                <td ' . $style2 . '>' . $vitals_result['bpd'] . '</td>
                <td ' . $style3 . '>' . $vitals_result['temperature'] . '</td>
                <td ' . $style4 . '>' . $vitals_result['pulse'] . '</td>
                <td ' . $style5 . '>' . $vitals_result['respiration'] . '</td>
                <td ' . $style6 . '>' . $vitals_result['oxygen_saturation'] . '</td>
                <td style="border: 1px solid black;font-size: 13px;font-weight:600;text-align: left;">' . $vitals_result['BMI'] . '</td>
            </tr>';
}
$html .= '</tbody>';
$html .= '</table>';




// echo $html;

$pdf = new mPDF();
$pdf->writeHTML($html);
if($pdf->Output('Vitals.pdf' . $path, 'I')){
	log_user_event('eMAR - Print Vitals', 'Patient Vitals Printed :'.$pid, $_SESSION['authUserID']);
}else{
	log_user_event('eMAR - Print Vitals', 'Patient Vitals Failed to print :'.$pid, $_SESSION['authUserID']);
}// D = Download, I = Inline
