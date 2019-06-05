<br>
<p class="publisher-title" style="font-size: 18px; font-weight: bold;" data-type="totals">Totals by Data Source</p>
<table id="table" class="table  table-bordered table-striped table-condensed custom-table" style="width: 100% !important;font-size: 13px;">
    <thead>
        <tr>
            <th style="min-width: 450px;">Date</th>
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
                    ?>
                    <tr>
                        <td>
                            <?php
                            echo "{$groups[$index]['date']} - ";
                            echo!empty($groups[$index]['code_type']) ? ucfirst($groups[$index]['code_type']) : '';
                            ?>
                        </td>
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
            }
        }
        ?>

    </tbody>
</table>
