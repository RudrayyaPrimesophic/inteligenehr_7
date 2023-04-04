<div class="row">
    <div class="col-md-6 mt-3">
        <a class="btn-default" href="<?php echo attr($url_webroot) ?>/interface/patient_file/summary/demographics.php?set_pid=<?= $pid ?>">Patient Dashboard</a>
    </div>

    <div class="col-md-6 mt-3 text-right" id="amed">
        <a href="javascript:void(0);" class="btn-default rx_modal <?= $disable_add_btns ?>" id="vital_check">Add Doctors Order</a>
        <a href="javascript:void(0);" class="btn-default rx_modal <?= $disable_add_btns ?>" onclick='editScripts("<?= $oeLink ?>")'>Add Medications</a>
        <a href="javascript:void(0);" class="btn-default  <?= $disable_add_btns ?>" data-toggle="modal" data-target="#ordeset_modal">Add Orderset</a>

        <div class="row mt-3">
            <div class="col-md-3 h25 inactive_med text-center"></div>
            <div class="col-md-3 h25 PRN_med text-center"></div>
            <div class="col-md-3 h25 stat_does_med text-center"></div>
            <div class="col-md-3 h25 from_orderset_med text-center"></div>
        </div>

        <div class="row">
            <div class="col-md-3  text-center">
                <p>Discontinued medication</p>
            </div>
            <div class="col-md-3  text-center">
                <p>PRN Medication</p>
            </div>
            <div class="col-md-3  text-center">
                <p>Stat Dose</p>
            </div>
            <div class="col-md-3  text-center">
                <p>From Orderset</p>
            </div>
        </div>

    </div>

</div>