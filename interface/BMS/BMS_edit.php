<?php

/**
 * POST-NUKE Content Management System
 * Based on:
 * PHP-NUKE Web Portal System - http://phpnuke.org/
 * Thatware - http://thatware.org/
 *
 * Purpose of this file: Directs to the start page as defined in config.php
 *
 * @author    Francisco Burzi
 * @author    Post-Nuke Development Team
 * @author    Brady Miller <brady.g.miller@gmail.com>
 * @copyright Copyright (c) 2001 by the Post-Nuke Development Team <http://www.postnuke.com/>
 * @copyright Copyright (c) 2019 Brady Miller <brady.g.miller@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */
require_once("../globals.php");
require_once "$srcdir/user.inc";
require_once "$srcdir/options.inc.php";
// require_once("$srcdir/acl.inc");

use OpenEMR\Common\Csrf\CsrfUtils;

?>
<html>

<head>
    <link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" href="<?= $webroot ?>/public/themes/dashboard.css">
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= $webroot ?>/public/themes/rounds.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="<?= $webroot ?>/interface/BMS/bms.css">
    <script src="<?= $webroot ?>/interface/BMS/bms.js"></script>
    <title>Edit BMS</title>
</head>

<body>
    <?php
    $date = date('l F j, Y, g:i a T', time());
    $time = date('g:i A', time());
    $time1 = strtotime($time);
    ?>
    <div class="container-fluid">
        <div class="row mt-2">
            <div class="col-md-5">
                <div class="page-header m-0">
                    <h2 id="header_tag">Bed Management System</h2>
                    <h5>Today <?= $date; ?></h5>
                </div>
            </div>
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-3">
                        <button class="" data-toggle="modal" data-target=".add_floor_bms">Add Floor Details</button>
                    </div>
                    <div class="col-md-3">
                        <button class="" data-toggle="modal" data-target=".add_wards_bms">Add Room Details</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            $query = "SELECT * FROM `bms_floor` ORDER BY `floor_no` ASC";
            $res = sqlStatement($query);
            foreach ($res as $row) {
                $floor_no = $row['floor_no'];
                $floor_id = $row['id'];
                $no_of_wards = $row['no_of_wards'];

            ?>
                <div class="col-md-12">
                    <div class="card bed_card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-4 floor_name">
                                    <h5><?= $row['floor_name'] ?></h5>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="button" class="btn btn-close update_floor_details" data-id="<?= $row['id'] ?>" data-floor_no="<?= $row['floor_no'] ?>" data-floor_name="<?= $row['floor_name'] ?>" data-no_of_wards="<?= $row['no_of_wards'] ?>">
                                        Edit
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <?php
                                    $id = $ward_row['id'];
                                    $qry_src_f = "SELECT count(*) as count FROM `bms` where `floor_no`='$floor_id' And discharge_date IS NULL";
                                    $res_src_f = sqlStatement($qry_src_f);
                                    $row_src_f = sqlFetchArray($res_src_f);
                                    if ($row_src_f['count'] > 0) { ?>
                                        <button type="button" class="btn btn-close delete_alert">
                                            Delete
                                        </button>
                                    <?php } else {  ?>
                                        <form action="<?= $webroot ?>/interface/BMS/floor_details_delete.php" id="floor_<?= $floor_id ?>" method="post">
                                            <input type="hidden" name="delete_floor_id" id="delete_floor_id" value="<?= $floor_id ?>">
                                            <button type="button" class="btn btn-close confirm_delete" data-id="<?= $floor_id; ?>">
                                                Delete
                                            </button>
                                        </form>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <table class="myTable table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Room Name</th>
                                            <th>Level of Care</th>
                                            <th>No of Beds</th>
                                            <th>Beds Starting From</th>
                                            <th>Beds Ending At</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query_ward = "SELECT * FROM `bms_wards` WHERE `floor_no`='$floor_id' ORDER BY `beds_starting_from` ASC";
                                        $res_ward = sqlStatement($query_ward);
                                        foreach ($res_ward as $ward_row) {
                                            $start = $ward_row['beds_starting_from'];
                                            $end = $ward_row['bed_ending_at'];
                                            $level_of_care = $ward_row['level_of_care'];

                                            $query_loc = "SELECT * FROM `list_options` WHERE `list_id`='level_of_care_list' and activity= 1 and  `option_id`='$level_of_care'";
                                            $res_loc = sqlStatement($query_loc);
                                            $row_loc = sqlFetchArray($res_loc);

                                        ?>
                                            <tr>
                                                <td>
                                                    <a href="javascript:void(0)" class="update_ward_details" data-id="<?= $ward_row['id'] ?>" data-ward_name="<?= $ward_row['ward_name'] ?>" data-floor_name="<?= $row['floor_name'] ?>" data-level_of_care="<?= $ward_row['level_of_care']; ?>" data-level_of_care_title="<?= $row_loc['title']; ?>" data-no_of_beds="<?= $ward_row['no_of_beds'] ?>" data-beds_starting_from="<?= $ward_row['beds_starting_from'] ?>">
                                                        <?= $ward_row['ward_name'] ?>
                                                    </a>
                                                </td>
                                                <td><?= $row_loc['title'] ?></td>
                                                <td><?= $ward_row['no_of_beds'] ?></td>
                                                <td><?= $ward_row['beds_starting_from'] ?></td>
                                                <td><?= $ward_row['bed_ending_at'] ?></td>
                                                <td>
                                                    <?php
                                                    $id = $ward_row['id'];
                                                    $qry_src = "SELECT count(*) as count FROM `bms` where `ward_name`='$id' And discharge_date IS NULL";
                                                    $res_src = sqlStatement($qry_src);
                                                    $row_src = sqlFetchArray($res_src);
                                                    if ($row_src['count'] > 0) { ?>
                                                        <a href="javascript:void(0)" class="w-alert">Delete</a>
                                                    <?php } else {  ?>
                                                        <form action="<?= $webroot ?>/interface/BMS/ward_details_delete.php" id="w_<?= $ward_row['id']; ?>" method="post">
                                                            <input type="hidden" name="delete_ward_id" value="<?= $ward_row['id'] ?>">
                                                            <a href="javascript:void(0)" class="w-delete" data-id="<?= $ward_row['id'] ?>">Delete</a>
                                                        </form>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>

<!-- Add Floor Details -->
<div class="modal fade add_floor_bms" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myExtraLargeModalLabel">Add BMS Floor Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= $webroot ?>/interface/BMS/insert_bms_floor.php" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 pt-2">
                            <span> Floor No. : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="number" class="form-control" name="floor_no" id="" required>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> Floor Name :</span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="text" class="form-control" name="floor_name" id="" required>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> No. of Rooms: </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="number" class="form-control" name="no_of_wards" id="" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Ward Details -->
<div class="modal fade add_wards_bms" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myExtraLargeModalLabel">Add BMS Room Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= $webroot ?>/interface/BMS/insert_ward_details.php" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 pt-2">
                            <span> Room Name : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="text" class="form-control" name="ward_name" id="" required>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> Floor No. : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <select name="floor_no" class="form-control" required>
                                <option value="">Select Floor Number</option>
                                <?php
                                $query = "SELECT * FROM `bms_floor`";
                                $res = sqlStatement($query);
                                foreach ($res as $row) {
                                    $floor_no = $row['floor_no'];
                                    $no_of_wards = $row['no_of_wards'];
                                    $query1 = "SELECT count(*) as count FROM `bms_wards` where `floor_no`='$floor_no'";
                                    $res1 = sqlStatement($query1);
                                    $row1 = sqlFetchArray($res1);
                                    $count = $row1['count'];
                                    if ($no_of_wards > $count) {
                                ?>
                                        <option value="<?= $row['id']; ?>"><?= $row['floor_name']; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> No of Beds : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="number" class="form-control" name="no_of_beds" required>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> Level of Care : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <?php
                            $query_loc = "SELECT * FROM `list_options` WHERE `list_id`='level_of_care_list' AND activity = 1";
                            $res_loc = sqlStatement($query_loc);
                            ?>
                            <select name="level_of_care" class="form-control" required>
                                <option value="">Select Level Of Care</option>
                                <?php foreach ($res_loc as $row_loc) { ?>
                                    <option value="<?= $row_loc['option_id']; ?>"><?= $row_loc['title']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> Bed No. Starting From : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="number" class="form-control" name="starting_from" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update floor name -->
<div class="modal fade" id="floor_update" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myExtraLargeModalLabel">Floor Details Update</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= $webroot ?>/interface/BMS/floor_details_update.php" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="floor_id" id="floor_id_update">
                        <div class="col-md-4 pt-2">
                            <span> Floor No : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="text" class="form-control" name="floor_no" id="floor_no_update" readonly required>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> Floor Name : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="text" class="form-control" name="floor_name_update" id="floor_name_update" required>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> No. of Rooms: </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="text" class="form-control" name="no_of_wards_update" id="no_of_wards_update" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- update ward details -->
<div class="modal fade" id="wards_bms_update" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myExtraLargeModalLabel">Add BMS Room Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= $webroot ?>/interface/BMS/update_ward_details.php" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="ward_id_update" id="ward_id_update">
                        <div class="col-md-4 pt-2">
                            <span> Room Name : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="text" class="form-control" name="ward_name_update" id="ward_name_update" required>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> Floor Name : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="text" class="form-control" id="floor_name_update_ward" readonly>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> No of Beds : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="text" class="form-control" name="no_of_beds_update" id="no_of_beds_update" required>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> Level of Care : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <?php
                            $query_loc = "SELECT * FROM `list_options` WHERE `list_id`='level_of_care_list' AND activity = 1";
                            $res_loc = sqlStatement($query_loc);
                            ?>
                            <select name="level_of_care_update" id="level_of_care_update" class="form-control" required>
                                <?php foreach ($res_loc as $row_loc) { ?>
                                    <option value="<?= $row_loc['option_id']; ?>"><?= $row_loc['title']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4 pt-2">
                            <span> Bed No. Starting From : </span>
                        </div>
                        <div class="col-md-8 pt-2">
                            <input type="text" class="form-control" name="starting_from_update" id="starting_from_update" readonly required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

</html>