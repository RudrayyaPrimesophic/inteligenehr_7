$(document).ready(function() {

    $('.myTable').DataTable();

    $(document).on("click", ".assign_beds", function(e) {
        floor_no = $(this).data("floor_no");
        ward_name = $(this).data("ward_name");
        floor_id = $(this).data("floor_id");
        ward_id = $(this).data("ward_id");
        bed_no = $(this).data("bed_no");
        bed_no_text = $(this).data("bed_no_text");
        lvl_of_care = $(this).data("lvl_of_care");

        document.getElementById("floor_no").value = floor_no;
        document.getElementById("ward_name").value = ward_name;
        document.getElementById("floor_id").value = floor_id;
        document.getElementById("ward_id").value = ward_id;
        document.getElementById("bed_no").value = bed_no;
        document.getElementById("bed_no_text").value = bed_no_text;
        $.ajax({
            url: "fetch_patient.php",
            type: "post",
            data: {
                lvl_of_care: lvl_of_care,
            },
            success: function(response) {
                console.log(response);
                $('#patient_find').html(response);
                $('#assign_beds').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    });

    $(document).on("click", ".reassign_beds", function(e) {
        id = $(this).data("id");
        document.getElementById("id").value = id;
		// By Rudrayya For Reassign Of Bed 16-09-2022 Start
        document.getElementById("reassign_id").value = id;
        // By Rudrayya For Reassign Of Bed 16-09-2022 End
        $('#discharge_update').modal('show');
    });

    $(document).on("click", ".update_floor_details", function(e) {
        id = $(this).data("id");
        floor_no = $(this).data("floor_no");
        floor_name = $(this).data("floor_name");
        no_of_wards = $(this).data("no_of_wards");

        document.getElementById("floor_id_update").value = id;
        document.getElementById("delete_floor_id").value = id;
        document.getElementById("floor_no_update").value = floor_no;
        document.getElementById("floor_name_update").value = floor_name;
        document.getElementById("no_of_wards_update").value = no_of_wards;

        $('#floor_update').modal('show');
    });

    $(document).on("click", ".update_ward_details", function(e) {
        id = $(this).data("id");
        ward_name = $(this).data("ward_name");
        floor_name = $(this).data("floor_name");
        level_of_care = $(this).data("level_of_care");
        level_of_care_title = $(this).data("level_of_care_title");
        no_of_beds = $(this).data("no_of_beds");
        beds_starting_from = $(this).data("beds_starting_from");

        select = document.getElementById('level_of_care_update');
        var opt = document.createElement('option');
        opt.value = level_of_care;
        opt.innerHTML = level_of_care_title;
        select.appendChild(opt);
        select.value = opt.value;

        document.getElementById("ward_id_update").value = id;
        document.getElementById("ward_name_update").value = ward_name;
        document.getElementById("floor_name_update_ward").value = floor_name;
        document.getElementById("no_of_beds_update").value = no_of_beds;
        document.getElementById("starting_from_update").value = beds_starting_from;

        $('#wards_bms_update').modal('show');
    });

    $(document).on("click", ".confirm_delete", function(e) {
        id = $(this).data("id");
        floor_id = "floor_" + id;
        if (confirm("Do You Want to Delete This Floor")) {
            $("#" + floor_id).submit();
        }
    });

    $(document).on("click", ".w-delete", function(e) {
        id = $(this).data("id");
        w_id = "w_" + id;
        if (confirm("Do You Want to Delete This Room")) {
            $("#" + w_id).submit();
        }
    });

    $(document).on("click", ".w-alert", function(e) {
        alert("This Room Has Admited Patient Please Realse Patients to delete the Room");
    });

    $(document).on("click", ".delete_alert", function(e) {
        alert("This Room Has Admited Patient Please Realse Patients to delete the Floor");
    });
	
	
    // By Rudrayya For Reassign Of Bed 16-09-2022 Start
    $(document).on("click", "#discharge_btn", function (e) {
        document.getElementById("discharge_btn").classList.add("active")
        document.getElementById("reassign_btn").classList.remove("active");

        document.getElementById("reassign_section").classList.add("d-none");
        document.getElementById("reassign_section").classList.remove("d-block");

        document.getElementById("discharge_section").classList.add("d-block");
        document.getElementById("discharge_section").classList.remove("d-none");
    });

    $(document).on("click", "#reassign_btn", function (e) {
        document.getElementById("reassign_btn").classList.add("active")
        document.getElementById("discharge_btn").classList.remove("active");

        document.getElementById("discharge_section").classList.add("d-none");
        document.getElementById("discharge_section").classList.remove("d-block");

        document.getElementById("reassign_section").classList.add("d-block");
        document.getElementById("reassign_section").classList.remove("d-none");
    });

    $(document).on("change", "#reassign_patient_foor", function (e) {
        var val = document.getElementById("reassign_patient_foor").value;
        $.ajax({
            url: "fetch_ward_details.php",
            type: "post",
            data: {
                floor_no: val,
            },
            success: function (response) {
                $('#room_no_div').html(response);
            }
        });
    });

    $(document).on("change", "#ward_no", function (e) {
        var val = document.getElementById("ward_no").value;
        $.ajax({
            url: "fetch_room_details.php",
            type: "post",
            data: {
                ward_name: val,
            },
            success: function (response) {
                $('#beds_no_div').html(response);
            }
        });
    });

    // By Rudrayya For Reassign Of Bed 16-09-2022 End
})