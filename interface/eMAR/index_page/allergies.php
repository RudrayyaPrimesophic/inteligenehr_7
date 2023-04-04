<div class="row" id="allergies" style="display: none;">
    <div class="col-md-12 mb-4 text-right">
        <button class="btn-default <?= $disable_add_btns ?>" id="add_allergies">Add Allergies</button>
    </div>
    <div class="col-md-12">
        <table id="" class="table e_MAR_tb table-striped">
            <thead>
                <tr>
                    <th>Allergy type</th>
                    <th>Allergen</th>
                    <th>Reaction type</th>
                    <th>Reaction</th>
                    <th>Begin date</th>
                    <th>Treatment</th>
                    <th>Status</th>
                    <th>Source of reporting</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $sql_query = "SELECT * FROM `form_allergies` where pid='" . $pid . "'";
                // $resultset = mysqli_query($conn, $sql_query) or die("database error:" . mysqli_error($conn));
                $res = sqlStatement($sql_query);
                while ($developer = sqlFetchArray($res)) {
                ?>
                    <tr id="<?php echo $developer['id']; ?>">
                        <td><?php echo $developer['allergy_type']; ?></td>
                        <td> <?php echo $developer['allergen'] ?></td>
                        <td><?php echo $developer['reaction_type']; ?></td>
                        <td><?php echo $developer['reaction']; ?></td>
                        <td><?= (strlen($developer['begin_date']) > 5) ? date("m-d-Y", strtotime($developer['begin_date'])) : ''; ?></td>
                        <td><?php echo $developer['treatment']; ?></td>
                        <td><?php echo $developer['status_code']; ?></td>
                        <td><?php echo $developer['source_of_report']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>