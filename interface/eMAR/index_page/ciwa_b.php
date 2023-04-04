<div class="row" id="ciwa_b" style="display: none;">
    <div class="col-md-12">
        <table id="" class="table e_MAR_tb table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>ID</th>
                    <th>CIWA Total Score</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_query = "SELECT form_new_ciwa_b.id,form_new_ciwa_b.date,form_new_ciwa_b.pid,form_new_ciwa_b.total_score FROM `form_new_ciwa_b` JOIN `forms` ON forms.form_id=form_new_ciwa_b.id where form_new_ciwa_b.pid = '" . $pid . "' AND forms.encounter='" . $encounter . "' order by date DESC";

                $res = sqlStatement($sql_query);
                while ($ciwab_result = sqlFetchArray($res)) {
                ?>
                    <tr>
                        <td><?= ($ciwab_result['date']) ? date("m-d-Y H:i:s", strtotime($ciwab_result['date'])) : ''; ?></td>
                        <td> <?php echo $ciwab_result['id']; ?></td>
                        <td><?php echo $ciwab_result['total_score']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>