<div class="row" id="pain_asses" style="display: none;">
    <div class="col-md-12">
        <table id="" class="table e_MAR_tb table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>ID</th>
                    <th>Pain Scale</th>
                    <th>Complaints of Pain</th>
                    <th>Mental Health</th>
                    <th>Suicide Thoughts?</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_query = "SELECT id,`datetime`,pid,pain_scale,complaints_of_pain,suicide_thoughts, mental_health FROM `nursing_note` where pid = '" . $pid . "' order by datetime DESC";
                $res = sqlStatement($sql_query);
                while ($nn_result = sqlFetchArray($res)) {
                ?>
                    <tr>
                        <td><?= ($nn_result['datetime']) ? date("m-d-Y H:i:s", strtotime($nn_result['datetime'])) : ''; ?></td>
                        <td> <?php echo $nn_result['id']; ?></td>
                        <td>
                            <?php $pain_scale = $nn_result['pain_sacle'];

                            if ($pain_scale > 0 && $pain_scale < 4) {
                                $pain_scalef = 'mild';
                            } elseif ($pain_scale >= 4 && $pain_scale < 6) {
                                $pain_scalef = 'moderate ';
                            } elseif ($pain_scale >= 6 && $pain_scale < 9) {
                                $pain_scalef = 'high';
                            } elseif ($pain_scale >= 9 && $pain_scale <= 10) {
                                $pain_scalef = 'severe';
                            } else {
                                $pain_scalef = 'No Pain';
                            }
                            ?> <?= $pain_scalef ?>
                        </td>

                        <td><?php echo $nn_result['complaints_of_pain']; ?></td>
                        <td><?php echo $nn_result['mental_health']; ?></td>
                        <td>
                            <p style="color: red"><?php echo $nn_result['suicide_thoughts']; ?></p>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>