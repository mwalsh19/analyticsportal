
<?php
if (!empty($groups)) {
    ?>
    <br>
    <p class="publisher-title" style="font-size: 18px; font-weight: bold;" data-type="totals">Totals by Data Source</p>
    <table id="table" class="table table-bordered table-condensed" style="width: 100% !important;font-size: 13px;">
        <thead>
            <tr>
                <th style="min-width: 150px;">DATE RANGE</th>
                <th style="min-width: 150px;">Ad Media</th>
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
            $f_mtd_label = '';
            $f_previous_date_label = '';
            //
            $f_total_mtd = 0;
            $f_total_previous_date = 0;
            //MONTH TO DATE
            $f_mtd_hired_total = 0;
            $f_mtd_attending_total = 0;
            $f_mtd_noq_total = 0;
            $f_mtd_noi_total = 0;
            $f_mtd_nor_total = 0;
            $f_mtd_duplicate_total = 0;
            $f_mtd_unqualified_total = 0;
            $f_mtd_do_not_contact_total = 0;
            //PREVIOUS DATE
            $f_hired_total = 0;
            $f_attending_total = 0;
            $f_noq_total = 0;
            $f_noi_total = 0;
            $f_nor_total = 0;
            $f_duplicate_total = 0;
            $f_unqualified_total = 0;
            $f_do_not_contact_total = 0;

            $totalItems = count($groups);
            for ($index = 0; $index < $totalItems; $index++) {

                if ($groups[$index]['isDataSource']) { //FILTER ONLY DATA SOURCES, EXCLUDE CALL SOURCE
                    foreach ($groups[$index]['items'] as $publisherName => $publisherItems) {
                        $total_total = 0;
                        $hired_total = 0;
                        $attending_total = 0;
                        $noq_total = 0;
                        $noi_total = 0;
                        $nor_total = 0;
                        $duplicate_total = 0;
                        $unqualified_total = 0;
                        $do_not_contact_total = 0;
                        foreach ($publisherItems as $item) {
                            $total_total += (int) $item['total'];
                            $hired_total += (int) $item['hired'];
                            $attending_total += (int) $item['attending_academy'];
                            $noq_total += (int) $item['not_qualified'];
                            $noi_total += (int) $item['not_interested'];
                            $nor_total += (int) $item['no_response'];
                            $duplicate_total += (int) $item['duplicate_app'];
                            $unqualified_total += (int) $item['unqualified_da'];
                            $do_not_contact_total += (int) $item['do_not_contact'];
                        }

                        if ($groups[$index]['isMDT']) {
                            if (empty($f_mtd_label)) {
                                $f_mtd_label = $groups[$index]['dateRange'];
                            }
                            //
                            $f_total_mtd+=$total_total;
                            //
                            $f_mtd_hired_total += $hired_total;
                            $f_mtd_attending_total += $attending_total;
                            $f_mtd_noq_total += $noq_total;
                            $f_mtd_noi_total += $noi_total;
                            $f_mtd_nor_total += $nor_total;
                            $f_mtd_duplicate_total += $duplicate_total;
                            $f_mtd_unqualified_total += $unqualified_total;
                            $f_mtd_do_not_contact_total += $do_not_contact_total;
                        }
                        if (!$groups[$index]['isMDT']) {
                            if (empty($f_previous_date_label)) {
                                $f_previous_date_label = $groups[$index]['dateRange'];
                            }
                            //
                            $f_total_previous_date+=$total_total;
                            //
                            $f_hired_total += $hired_total;
                            $f_attending_total += $attending_total;
                            $f_noq_total += $noq_total;
                            $f_noi_total += $noi_total;
                            $f_nor_total += $nor_total;
                            $f_duplicate_total += $duplicate_total;
                            $f_unqualified_total += $unqualified_total;
                            $f_do_not_contact_total += $do_not_contact_total;
                        }
                        ?>
                        <tr class="<?php echo $groups[$index]['isMDT'] ? 'mdt-row' : 'previous-date'; ?>">
                            <td><?= $groups[$index]['dateRange']; ?></td>
                            <td><?= $groups[$index]['label']; ?></td>
                            <td><?= $total_total; ?></td>
                            <td><?= $hired_total; ?></td>
                            <td><?= $attending_total; ?></td>
                            <td><?= $noq_total; ?></td>
                            <td><?= $noi_total; ?></td>
                            <td><?= $nor_total; ?></td>
                            <td><?= $duplicate_total; ?></td>
                            <td><?= $unqualified_total; ?></td>
                            <td><?= $do_not_contact_total; ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    foreach ($groups[$index]['items'] as $publisherName => $publisherItems) {
                        $total_total = 0;
                        foreach ($publisherItems as $item) {
                            $total_total += (int) $item['calls'];
                        }
                        if ($groups[$index]['isMDT']) {
                            if (empty($f_mtd_label)) {
                                $f_mtd_label = $groups[$index]['dateRange'];
                            }
                            //
                            $f_total_mtd+=$total_total;
                        }
                        if (!$groups[$index]['isMDT']) {
                            if (empty($f_previous_date_label)) {
                                $f_previous_date_label = $groups[$index]['dateRange'];
                            }
                            //
                            $f_total_previous_date+=$total_total;
                        }
                        ?>
                        <tr class="<?php echo $groups[$index]['isMDT'] ? 'mdt-row' : 'previous-date'; ?>">
                            <td><?= $groups[$index]['dateRange']; ?></td>
                            <td><?= $groups[$index]['label']; ?></td>
                            <td><?= $total_total; ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>

        </tbody>
        <tfoot style="background-color: #c4d8ed; color: #388ebd;">
            <tr style="background-color: #fff;">
                <th colspan="11">&nbsp;</th>
            </tr>
            <tr>
                <th><?= $f_mtd_label; ?></th>
                <th><strong>Tenstreet Leads TOTALS:</strong></th>
                <th><?= $f_total_mtd ?></th>
                <th><?= $f_mtd_hired_total ?></th>
                <th><?= $f_mtd_attending_total ?></th>
                <th><?= $f_mtd_noq_total ?></th>
                <th><?= $f_mtd_noi_total ?></th>
                <th><?= $f_mtd_nor_total ?></th>
                <th><?= $f_mtd_duplicate_total ?></th>
                <th><?= $f_mtd_unqualified_total ?></th>
                <th><?= $f_mtd_do_not_contact_total ?></th>
            </tr>
            <tr>
                <th><?= $f_previous_date_label; ?></th>
                <th><strong>Tenstreet Leads TOTALS:</strong></th>
                <th><?= $f_total_previous_date ?></th>
                <th><?= $f_hired_total ?></th>
                <th><?= $f_attending_total ?></th>
                <th><?= $f_noq_total ?></th>
                <th><?= $f_noi_total ?></th>
                <th><?= $f_nor_total ?></th>
                <th><?= $f_duplicate_total ?></th>
                <th><?= $f_unqualified_total ?></th>
                <th><?= $f_do_not_contact_total ?></th>
            </tr>
        </tfoot>
    </table>
    <?php
}
?>
