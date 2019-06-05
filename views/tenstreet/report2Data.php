<?php
foreach ($group1 as $name => $subgroup) {
    ?>
    <br>
    <p class="publisher-title" style="font-size: 18px; font-weight: bold;"><?= $name; ?></p>
    <?php
    echo $this->render('_report2_table', ['subgroup' => $subgroup, 'code_type' => 'Referrer']);
}
?>
<?php
foreach ($group2 as $name => $subgroup) {
    ?>
    <br>
    <p class="publisher-title" style="font-size: 18px; font-weight: bold;"><?= $name; ?></p>
    <?php
    echo $this->render('_report2_table', ['subgroup' => $subgroup, 'code_type' => 'Source']);
}
?>
