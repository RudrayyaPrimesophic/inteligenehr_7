<?php

include("header.php");

$pid = $GLOBALS['pid'];
$encounter = $_SESSION['encounter'];

$sql1 = "SELECT COUNT(*) as count FROM `form_encounter` WHERE `encounter`=$encounter AND status = 'open'";
$res1 = sqlStatement($sql1);
$r_details = sqlFetchArray($res1);


if ($r_details['count'] > 0) {
    $p_delete = 0;
} else {
    $p_delete = 1;
}


// $patient_status = get_patient_status($pid);
// $status_pat = $patient_status['status'];

$url_webroot = $GLOBALS['webroot'];

$providers = [];

$sql = 'SELECT id, fname, lname FROM users';
$res = sqlStatement($sql);
while ($row = sqlFetchArray($res)) {
    $providers[$row['id']] = $row;
}

include("scripts_top.php");

?>

<body>
    <?php

    if ($encounter < 1) : ?>
        <h3 class="text-center mt-5">There is no active encounter selected</h3>
        <h5 class="text-center mt-2">Please select an encounter to view the eMAR</h5>
        <div class="row">
            <div class="col-md-12 text-center mt-2">
                <a class="btn-default" href="<?php echo attr($url_webroot) ?>/interface/patient_file/summary/demographics.php?set_pid=<?= $pid ?>">
                    Goto Patient Dashboard</a>
            </div>
        </div>
    <?php exit;
    endif; ?>

    <?php include("index_page/sidebar.php"); ?>

    <?php $oeLink = $GLOBALS['webroot'] . "/controller.php?prescription&list&id=" . attr_url($pid); ?>

    <div class="content">
        <!-- Top Bar -->
        <?php include("index_page/top_bar.php");  ?>

        <br>

        <!-- todays Meds -->
        <?php include("index_page/todays_meds.php");  ?>

        <!-- Upcoming medications -->
        <?php include("index_page/upcoming_med_logs.php");  ?>

        <!-- Meds Prescribing -->
        <?php include("index_page/meds_pres.php");  ?>

        <!-- Meds Administerd -->
        <?php include("index_page/meds_admin.php");  ?>

        <!-- Verbal Orders -->
        <?php include("index_page/verbal_orders.php");  ?>

        <!-- Medication Not Administerd -->
        <?php include("index_page/meds_not_admin.php");  ?>

        <!-- Medication Brought In -->
        <?php include("index_page/meds_broughtin.php");  ?>

        <!--Allergies-->
        <?php include("index_page/allergies.php");  ?>

        <!-- Vitals -->
        <?php include("index_page/vitals.php");  ?>

        <!--Cows-->
        <?php include("index_page/cows.php");  ?>

        <!--Ciwa-->
        <?php include("index_page/ciwa.php");  ?>

        <!--Ciwa_b-->
        <?php include("index_page/ciwa_b.php");  ?>

        <!--Pain Assesments-->
        <?php include("index_page/pain_asses.php");  ?>

        <!-- iRx Medication -->
        <?php include("index_page/iRx.php");  ?>
    </div>

    <!--Modals-->
    <?php include("index_page/modals.php");  ?>

    <!-- Orderset -->
    <?php include("orderset.php"); ?>

</body>

<!-- Scripts Bottom -->
<?php include("scripts_bottom.php"); ?>