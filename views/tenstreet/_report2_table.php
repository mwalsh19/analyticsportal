<table id="table" class="table table-bordered table-striped table-condensed" style="width: 100% !important;font-size: 13px;">
    <thead>
        <tr>
            <th style="min-width: 450px;"><?php echo $code_type ?> Code</th>
            <th>Total</th>
            <th>Hired</th>
            <th>Attending Academy</th>
            <th>Not Qualified</th>
            <th>Not Interested</th>
            <th>No Response</th>
            <th>Duplicate App</th>
            <th>Unqualified Da</th>
            <th>Do Not Contact</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total_total = 0;
        $hired_total = 0;
        $attending_total = 0;
        $noq_total = 0;
        $noi_total = 0;
        $nor_total = 0;
        $duplicate_total = 0;
        $unqualified_total = 0;
        $do_not_contact_total = 0;
        foreach ($subgroup as $item) {
            $total_total += (int) $item['total'];
            $hired_total += (int) $item['hired'];
            $attending_total += (int) $item['attending_academy'];
            $noq_total += (int) $item['not_qualified'];
            $noi_total += (int) $item['not_interested'];
            $nor_total += (int) $item['no_response'];
            $duplicate_total += (int) $item['duplicate_app'];
            $unqualified_total += (int) $item['unqualified_da'];
            $do_not_contact_total += (int) $item['do_not_contact'];
            ?>
            <tr>
                <td><?= $item['referrer_code']; ?></td>
                <td><?= $item['total']; ?></td>
                <td><?= $item['hired']; ?></td>
                <td><?= $item['attending_academy']; ?></td>
                <td><?= $item['not_qualified']; ?></td>
                <td><?= $item['not_interested']; ?></td>
                <td><?= $item['no_response']; ?></td>
                <td><?= $item['duplicate_app']; ?></td>
                <td><?= $item['unqualified_da']; ?></td>
                <td><?= $item['do_not_contact']; ?></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
    <tfoot>
        <tr id="totals-row">
            <th>Total</th>
            <th><?= $total_total; ?></th>
            <th><?= $hired_total; ?></th>
            <th><?= $attending_total; ?></th>
            <th><?= $noq_total; ?></th>
            <th><?= $noi_total; ?></th>
            <th><?= $nor_total; ?></th>
            <th><?= $duplicate_total; ?></th>
            <th><?= $unqualified_total; ?></th>
            <th><?= $do_not_contact_total; ?></th>
        </tr>
    </tfoot>
</table>
