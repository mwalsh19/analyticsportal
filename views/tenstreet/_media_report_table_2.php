<table class="table table-bordered table-striped table-condensed" style="width: 100% !important;font-size: 13px;">
    <thead>
        <tr>
            <th style="min-width: 450px;"><?php echo $code_type ?></th>
            <th>Tracking Number</th>
            <th>Calls</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total_calls = 0;
        foreach ($subgroup as $item) {
            $total_calls += (int) $item['calls'];
            ?>
            <tr>
                <td><?= $item['referrer_code']; ?></td>
                <td><?= $item['tracking_number']; ?></td>
                <td><?= $item['calls']; ?></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
    <tfoot>
        <tr id="totals-row">
            <th>Total</th>
            <th></th>
            <th><?= $total_calls; ?></th>
        </tr>
    </tfoot>
</table>
