<?php
foreach ($groups as $name => $subgroup) {
    ?>
    <br>
    <p class="publisher-title" style="font-size: 18px; font-weight: bold;"><?= $name; ?></p>
    <?php
    echo $this->render('_grouped_by_publisher_table_2', ['subgroup' => $subgroup]);
}
?>

<br>
<p class="publisher-title" style="font-size: 18px; font-weight: bold;">Uncategorized</p>
<?php
echo $this->render('_grouped_by_publisher_table_2', ['subgroup' => $uncategorized]);
