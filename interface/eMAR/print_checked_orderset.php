<?php
require_once("../globals.php");

use OpenEMR\Common\Csrf\CsrfUtils;
use Mpdf\Mpdf;

$config_mpdf = array(
    'tempDir' => $GLOBALS['MPDF_WRITE_DIR'],
    'mode' => $GLOBALS['pdf_language'],
    'format' => $GLOBALS['pdf_size'],
    'default_font_size' => '',
    'default_font' => '',
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 15,
    'margin_bottom' => 15,
    'margin_header' => '',
    'margin_footer' => '',
    'orientation' => $GLOBALS['pdf_layout'],
    'shrink_tables_to_fit' => 1,
    'use_kwt' => true,
    'autoScriptToLang' => true,
    'keep_table_proportions' => true
);
$pdf = new mPDF($config_mpdf);
$pdf->SetDisplayMode('real');
if ($_SESSION['language_direction'] == 'rtl') {
    $pdf->SetDirectionality('rtl');
}

$style_sheet = '.ftitlecell1 {
    width: 33%;
    vertical-align: top;
    text-align: left;
    font-size: round(9 * 1.56)pt;
    font-weight: bold;
}  .ftitlecell2 {
    width: 33%;
    vertical-align: top;
    text-align: right;
    font-size: 9pt;
} .ftitlecellm {
    width: 34%;
    vertical-align: top;
    text-align: center;
    font-size: round(9 * 1.56)pt;
    font-weight: bold;
} 
td {
    font-size: 18px;
    padding: 5px;
}

.separator { margin-top: 8px; }
.mb-8 { margin-bottom: 8px; }
.mt-8 { margin-top: 8px; }
.ml-10 { margin-left: 50px; }

.col-1, .col-2, .col-3, .col-4, .col-5, .col-6, 
.col-7, .col-8, .col-9, .col-10, .col-11, .col-12 { float: left;}

.col-6 {width: 50%;}
.col-3 {width: 25%;}
.col-4 {width: 33.33%; }
.col-9 {width: 75%;}
.col-12 {width: 100%;}
.col-2 {width: 16.66%; text-tranform: capitalize;}
.col-5 {width: 41.66%;}
.col-7 {width: 58.33%;}
.col-8 {width: 62.66%;}
.col-10 {width: 83%;}
.col-11 {width: 91.66%;}
.col-1 {width: 8.33%;}

.vis-hidden {
    visibility: hidden;
}

.flex {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-between;
}
.ps-2 {
    padding-left: 2px;
}

.small {font-size: 10px; float: left;  width:75px; }
.med {font-size: 10px; float: left;  width:100px;   }
.med1 {font-size: 10px; float: left;  width:150px;  }
.big {font-size: 10px; float: left;  width:175px;   }
.big1 {font-size: 10px; float: left;  width:200px;  }
.xl {font-size: 10px; float: left;  width:250px;   }
.xxl {font-size: 10px; float: left;  width:275px;  }

.small1 {font-size: 9px; float: left;  width:120px; max-width:190px; }
.small-rad-10 {font-size: 10px; float: left;  width:30px; max-width:60px;}
 p {font-size: 10px; padding: 0px; margin: 0px;}
 p.small {font-size: 10px; float: left;  width:70px; max-width:100px;margin-top:0px; }
 .smalltext {font-size: 9px; padding: 0px; margin: 0px; }
.minitext { font-size: 7px; padding: 0px; margin: 0px;  width:30px; float:left;}
.minitext2 { font-size: 7px; padding: 0px; margin: 0px; width:95px; float:left;}
.minitext3 { font-size: 7px; padding: 0px; margin: 0px; width:50px; float:left;}
.minitext4 { font-size: 7px; padding: 0px; margin: 0px; width:50px; float:left;}
.p-label {font-weight: 500; font-size: 10px;}
.p-value { padding: 0px;}

.text-center {text-align: center;}
.mt-2 {margin-top: 5px;}
h2 { padding: 0px; margin: 0px; font-size: 18px;}
h3 { padding: 0px; margin: 0px; font-size: 15px;}
h4 { padding: 0px; margin: 0px; font-size: 12px;}
';



$orderset_id = $_GET['orderset_id'];
$medication_id = $_GET['medication_id'];
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;
$approved_by = isset($_GET['approved_by']) ? $_GET['approved_by'] : null;
$varbal = isset($_GET['end_date']) ? $_GET['varbal'] : null;






$sql_qry = "SELECT orderset.*, users.fname, users.lname FROM `orderset` join users on users.id = orderset.created_by WHERE orderset.id=". $orderset_id . " LIMIT 0,1";
$res = sqlStatement($sql_qry);
$orderset = sqlFetchArray($res);

$med_res=[];
if(!empty($medication_id)){

$sql_qry = "SELECT * FROM orderset_medication WHERE orderset_id=". $orderset_id ." AND id IN (".$medication_id .") ORDER BY day ASC";
$med_res = sqlStatement($sql_qry);

}
$pdf->WriteHTML($style_sheet, \Mpdf\HTMLParserMode::HEADER_CSS);

$pdf->writeHTML(print_orderset($orderset, $med_res,$start_date,$end_date,$approved_by,$varbal), 2);
$pdf->SetTitle($orderset['name']);
echo $pdf->Output('form.pdf', 'I');

function print_orderset($orderset, $med_res,$start_date='',$end_date='',$approved_by='',$varbal=''){
    $html =  '<html>

    <head>
    </head>
    
    <body class="body_top" style="width: 100%;">
        <div class="container w-100" style="max-width:100%;">
            <div class="row">
                <div class="col-12" id="header-container">
                    <h3 class="text-center">'. $orderset['name'].'</h3>
                </div>
            </div>
            <br>
    ';

    $html .= '<div class="row mb-3 mt-2">';
    if(!empty($start_date)){
       
       $html .= '<div class="col-2"> <p>Start Date</p></div>';
       $html .= '<div class="col-3 "><p>'.$start_date.'</p></div>';
   }
   if (!empty($end_date)) {
       $html .= '<div class="col-2"> <p>End Date</p></div>';
       $html .= '<div class="col-3 "><p>'.$end_date.'</p></div>';
       
   } 
       $html .= '<div class="col-2 "></div></div>';
    
    $count = 0;
    $count_drug = [];
    while($row =  sqlFetchArray($med_res)) : 
        $count++;
        $medication_units = [1 => 'mg', 'mg/1cc', 'mg/2cc', 'mg/3cc', 'mg/4cc', 'mg/5cc', 'mcg', 'grams', 'mL'];
        $route_data = [0 => '', 1 => 'Oral', 2 => 'Rectal', 3 => 'Topical', 4 => 'Transdermal', 5 => 'Sublingual', 6 => 'Vaginal', 7 => 'Percutaneous', 8 => 'Subcutaneous', 9 => 'Intramuscular', 10 => 'Intra-arterial', 11 => 'Intravenous', 12 => 'Nasal', 13 => 'Other/Miscellaneous'];
        $intervals = [0 => '', 1  => 'b.i.d. (Twice a day)', 2  => 't.i.d. (Thrice a day)', 3  => 'q.i.d. (Four times a day)', 4  => 'q.3h (Every 3 Hours)', 5  => 'q.4h (Every 4 Hours)', 6  => 'q.5h (Every 5 Hours)', 7  => 'q.6h (Every 6 Hours)', 8  => 'q.8h (Every 8 Hours)', 9  => 'q.d. (Once a day)', 10 => 'a.c. (Before Meals)', 11 => 'p.c. (After Meals)', 12 => 'a.m. (Morning)', 13 => 'p.m. (Evening)', 14 => 'ante (In front of)', 17 => 'p.r.n. (When necessary)', 18 => 'Stat /One Time Dose', 19 => 'Every 30 Mins', 20 => 'Every 1 Hour', 21 => 'Every 2 hour'];
        if (isset($count_drug[$row['day']])) {
            $count_drug[$row['day']]++;
        } else {
            if ($count != 1) {
                $html .= '</div><!-- end of day-->';
            }
            $count_drug[$row['day']] = 1;

                $html .=  "<hr id='hr_" . $row['id'] . "'>";
                $html .=  '<div id="orderset_day_' . ($row['day'] + 0) . '">';
                $html .=  '<div class="mb-3 col-12"><p>Day ' . $row['day'] . '</p> 
                          </div>';
                $html .=  "<hr>";
            // $html .=  '<div class="row">
            //            <div class="mb-3 col-4">
            //                 <p><b>Drug</b></p> 
            //             </div>
            //             <div class="mb-3 col-1">
            //                 <p><b>Frequency</b></p> 
            //             </div>
            //             <div class="mb-3 col-1">
            //                 <p><b>Total Dose</b></p> 
            //             </div>
            //             <div class="mb-3 col-2">
            //                 <p><b>Interval</b></p> 
            //             </div>
            //             <div class="mb-3 col-2">
            //                 <p><b>Note</b></p> 
            //             </div>
            //         </div>';
            // $html .=  "<hr>";        
            
           
        }
        if ($row['drug_name'] != '') {
            $is_prn_class = ($row['intervals'] == 18) ? ' stat_does_med' : '';
            $is_prn_class = ($row['is_prn'] || $row['intervals'] == 17) ? 'cls_prn' : $is_prn_class;
            $html .=  '<div class="row p-2 ' . $is_prn_class . '" id="drug_' . $row['id'] . '">';

            $html .=  '<div class="mb-3 col-4">
                        <p><b>' . $row['drug_name'] . '</b></p> 
                    </div>';

            if ($row['set_type']) {
                $html .=  '<div class="mb-3 col-1">
                    <p>' . $route_data[$row['route']] . '</p> 
                    </div>';
                $html .=  '<div class="mb-3 col-1">
                    <p>' . $row['units'] . $medication_units[$row['unit']] . '</p> 
                    </div>';
                $html .=  '<div class="mb-3 col-2">
                    <p>' . $intervals[$row['intervals']] . '</p> 
                    </div>';
            } else {
                $html .=  '<div class="mb-3 col-4">
                    <p>' . $row['dosage'] . '</p> 
                    </div>';
            }
            $html .=  '<div class="mb-3 col-2">
                    <p>' . $row['note'] . '</p> 
                    </div>';
            $html .=   "</div>";
        }

    endwhile; 


    $html .= ' <br>
               <hr>
              <div class="row mt-3">
              <div class="col-3">&nbsp;
              </div>
              <div class="col-3">
                <p>Approved By:</p>
              </div>';
              
              if($approved_by==141){
    $html .=  '<div class=" col-3">
               <p>Nicole White</p> </div>';
               }
               if($approved_by==77){
    $html .=  '<div class="col-3">
               <p>Flora Sadri-Azarbayejani, DO</p> </div></div>';
               }
    $html .=' <div class="row mt-3">
              <div class="col-3">&nbsp;
              </div>
              <div class="col-3">
                 <p>Verbal:</p>
              </div>';

                if($varbal==by_phone){
    $html .=  '<div class=" col-3">
               <p>By Phone</p> </div>';
                }
                if($varbal==in_person){
    $html .=  '<div class=" col-3">
                <p>In Person</p> </div></div>';
                }
                
    $html .= '
            </div>
            </body>
            </html>';
    
    return $html;
}
?>
<html>

<head>
</head>

<body class="body_top" style="width: 100%;">
    <div class="container w-100" style="max-width:100%;">
        <div class="row">
            <div class="col-12" id="header-container">
                <?= $orderset['name'] ?>
            </div>
            <?php 
                $count = 0;
                $count_drug = [];
                while($row =  sqlFetchArray($res)) : 
                    $count++;
                    if (isset($count_drug[$row['day']])) {
                        $count_drug[$row['day']]++;
                    } else {
                        if ($count != 1) {
                            echo '</div><!-- end of day-->';
                        }
                        $count_drug[$row['day']] = 1;
                        echo "<hr id='hr_" . $row['id'] . "'>";
                        echo '<div id="orderset_day_' . ($row['day'] + 0) . '">';
                    }
                    if ($row['drug_name'] != '') {
                        $is_prn_class = ($row['intervals'] == 18) ? ' stat_does_med' : '';
                        $is_prn_class = ($row['is_prn'] || $row['intervals'] == 17) ? 'cls_prn' : $is_prn_class;
                        echo '<div class="row p-2 ' . $is_prn_class . '" id="drug_' . $row['id'] . '">';
        
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
                        echo  "</div>";
                    }
            ?>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>