
<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\XmlSegment;
use app\models\XmlPublisher;

$status = isset($_GET['status']) ? $_GET['status'] : '';
$segment = isset($_GET['segment']) ? $_GET['segment'] : '';
$publisher = isset($_GET['publisher']) ? $_GET['publisher'] : '';
?>
<div class="pull-left" style="margin-top: 10px;">
    Filter by status: <?php
    echo Html::dropDownList('status-filter', $status, [
        1 => 'Enabled',
        0 => 'Disabled'
            ], [
        'class' => 'form-control input-sm',
        'id' => 'status-filter',
        'prompt' => 'Select an option'
    ]);
    ?>
</div>
<div class="pull-left" style="margin-top: 10px; margin-left: 20px;">
    Filter by publisher:  <?php
    echo Html::dropDownList('publisher-filter', $publisher, ArrayHelper::map(XmlPublisher::find()->orderBy('name ASC')->all(), 'id_publisher', 'name'), [
        'class' => 'form-control input-sm',
        'id' => 'publisher-filter',
        'prompt' => 'Select an option'
    ]);
    ?>
</div>
<div class="pull-left" style="margin-top: 10px; margin-left: 20px;">
    Filter by segment: <?php
    echo Html::dropDownList('segment-filter', $segment, ArrayHelper::map(XmlSegment::find()->orderBy('name ASC')->all(), 'id_segment', 'name'), [
        'class' => 'form-control input-sm',
        'id' => 'segment-filter',
        'prompt' => 'Select an option'
    ]);
    ?>
</div>
