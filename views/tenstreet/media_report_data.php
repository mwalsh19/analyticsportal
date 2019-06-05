
<?php
if (!empty($groups)) {
    $code_type = '';
    $totalItems = count($groups);

    echo $this->render('_media_report_table_totals', ['groups' => $groups]);

    for ($index = 0; $index < $totalItems; $index++) {
        $type = $groups[$index]['code_type'];
        $code_type = !empty($type) ? ucfirst($type) . ' code' : 'N/A';
        $isDataSource = $groups[$index]['isDataSource'];

        foreach ($groups[$index]['items'] as $publisherName => $publisherItems) {
            ?>
            <br>
            <p class="publisher-title" style="font-size: 18px; font-weight: bold;" data-type="<?php echo $type ?>"><?= $publisherName; ?></p>
            <?php
            if ($isDataSource) {
                echo $this->render('_media_report_table', ['subgroup' => $publisherItems, 'code_type' => $code_type]);
            } else {
                echo $this->render('_media_report_table_2', ['subgroup' => $publisherItems, 'code_type' => $code_type]);
            }
        }
    }
}
?>
