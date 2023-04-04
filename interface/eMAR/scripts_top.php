
<script>
    function editScripts(url) {
        var AddScript = function() {
            var __this = $(this);
            __this.find("#clearButton").css("display", "");
            __this.find("#backButton").css("display", "");
            __this.find("#addButton").css("display", "none");
            var iam = top.tab_mode ? top.frames.editScripts : window[1];
            iam.location.href = '<?php echo $GLOBALS['webroot'] ?>/controller.php?prescription&edit&id=0&pid=' + <?php echo js_url($pid); ?>;
        };
        var ListScripts = function() {

            var __this = $(this);
            __this.find("#clearButton").css("display", "none");
            __this.find("#backButton").css("display", "none");
            __this.find("#addButton").css("display", "");
            var iam = top.tab_mode ? top.frames.editScripts : window[1];
            iam.location.href = '<?php echo $GLOBALS['webroot'] ?>/controller.php?prescription&list&id=' + <?php echo js_url($pid); ?>;
        };

        let title = <?php echo xlj('Prescriptions'); ?>;
        let w = 910; // for weno width

        dlgopen(url, 'editScripts', w, 300, '', '', {
            buttons: [{
                    text: <?php echo xlj('Add'); ?>,
                    close: false,
                    id: 'addButton',
                    class: 'btn-primary btn-sm',
                    click: AddScript
                },
                {
                    text: <?php echo xlj('Clear'); ?>,
                    close: false,
                    id: 'clearButton',
                    style: 'display:none;',
                    class: 'btn-primary btn-sm',
                    click: AddScript
                },
                {
                    text: <?php echo xlj('Back'); ?>,
                    close: false,
                    id: 'backButton',
                    style: 'display:none;',
                    class: 'btn-primary btn-sm',
                    click: ListScripts
                },
                {
                    text: <?php echo xlj('Done'); ?>,
                    close: true,
                    id: 'doneButton',
                    class: 'btn-default btn-sm'
                }
            ],
            onClosed: 'reload',
            allowResize: true,
            allowDrag: true,
            dialogId: 'editscripts',
            type: 'iframe',
        });

    }

    function editAdminstrate(value = 1) {
        const multimeds = $('#multimeds').val();
        const url = '<?php echo $GLOBALS['webroot'] . "/interface/eMAR/administrative_popup.php?id=" . attr_url($pid);  ?>&action='+ value + '&medlogs=' + multimeds;
        console.log(url);
        var AddScript = function() {
            var __this = $(this);
           
            __this.find("#backButton").css("display", "");
           

            var iam = top.tab_mode ? top.frames.editAdminstrate : window[1];
            iam.location.href = '<?php echo $GLOBALS['webroot'] ?>/interface/eMAR/index.php';
        };
        var ListScripts = function() {

            var __this = $(this);
     
            __this.find("#backButton").css("display", "none");
           
            var iam = top.tab_mode ? top.frames.editAdminstrate : window[1];
            iam.location.href =  '<?php echo $GLOBALS['webroot'] ?>/interface/eMAR/administrative_popup.php';
        };

        var administrateScript = function() {
            var __this = $(this);
            console.log(__this);
        };


        let title = <?php echo xlj('Administrative'); ?>;
        let w = 1042; // for weno width

        dlgopen(url, 'editAdminstrate', w, 400, '', '', {
            // buttons: [
            //     {
            //         text: <?php //echo xlj('Done'); ?>,
            //         close: true,
            //         id: 'doneButton',
            //         class: 'btn-default btn-sm',
            //     }
            // ],

            onClosed: 'reload',
            allowResize: true,
            allowDrag: true,
            dialogId: 'editAdminstrate',
            type: 'iframe',
        });

    }
	
	    function editMedsBrought(id) {
        const url = '<?php echo $GLOBALS['webroot'] . "/interface/eMAR/edit_meds_broughtin_popup.php" ?>?id='+ id;
        var AddScript = function() {
            var __this = $(this);

            __this.find("#backButton").css("display", "");


            var iam = top.tab_mode ? top.frames.editMedsBrought : window[1];
            iam.location.href = '<?php echo $GLOBALS['webroot'] ?>/interface/eMAR/index.php';
        };
        var ListScripts = function() {

            var __this = $(this);

            __this.find("#backButton").css("display", "none");

            var iam = top.tab_mode ? top.frames.editMedsBrought : window[1];
            iam.location.href ='<?php echo $GLOBALS['webroot'] . "/interface/eMAR/edit_meds_broughtin_popup.php" ?>?id='+ id;
        };

        var administrateScript = function() {
            var __this = $(this);
            console.log(__this);
        };


        let title = <?php echo xlj('Edit Meds Brought-In'); ?>;
        let w = 1018; // for weno width

        dlgopen(url, 'editMedsBrought', w, 400, '', title, {
            onClosed: 'reload',
            allowResize: true,
            allowDrag: true,
            dialogId: 'editMedsBrought',
            type: 'iframe',
        });

    }
</script>