
<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\XmlPublisher;

$publisher = !empty($_GET['publisher']) ? (int) $_GET['publisher'] : 0;
$segment = !empty($_GET['segment']) ? (int) $_GET['segment'] : 0;

if (!empty($publisher) && $model->isNewRecord) {
    $model->tbl_xml_publisher_id_publisher = $publisher;
}

if (!empty($segment) && $model->isNewRecord) {
    $model->tbl_xml_segment_id_segment = $segment;
}
$test = (int) $model->enabled;
$model->enabled = isset($model->enabled) ? $model->enabled : 1;

$this->registerJs("
    var segmentDrop = $('#segment-select');
        publisherDrop = $('#publisher-select'),
        segmentVar = {$segment};

    publisherDrop.on('change', function(){
        var option = $(this).find('option:selected').val();
            segmentDrop.attr('disabled', true);
            $.get('get-segments', {id_publisher:option}, function(data){
                segmentDrop.html('');
                if(data != ''){
                    segmentDrop.html(data);
                    segmentDrop.attr('disabled', false);

                    if(segmentVar){
                        segmentDrop.val(segmentVar).change();
                    }
                }
            });
    });
");
$segmentsItems = [];

if (!empty($model->tbl_xml_publisher_id_publisher)) {
    $publisher = \app\models\XmlPublisher::find()->where('id_publisher=:id_publisher', [':id_publisher' => $model->tbl_xml_publisher_id_publisher])->with('tblXmlSegmentIdSegments')->one();
    $segmentsItems = ArrayHelper::map($publisher->tblXmlSegmentIdSegments, 'id_segment', 'name');
}


$form = ActiveForm::begin([
            'id' => 'form',
            'options' => [
            ]
        ]);
?>

<div class="form-group">
    <?php
    if ($model->isNewRecord) {
        ?>
        <p>
            Please add one or multiple url/s separate by break line, one url by line.<br>
            <strong>Example:</strong><br>
            <span class="text-red">http://example1.com</span><br>
            <span class="text-red">http://example2.com</span>
        </p>
        <label>Urls</label>
        <?= Html::activeTextarea($model, 'url', ['class' => 'form-control input-sm', 'rows' => 10]); ?>
        <?php
    } else {
        ?>
        <label>Url</label>
        <?= Html::activeTextInput($model, 'url', ['class' => 'form-control input-sm']); ?>
        <?php
    }
    ?>
    <?= Html::error($model, 'url', ['class' => 'help-block errorMessage']); ?>

</div>
<div class="form-group">
    <label>Publisher</label>
    <?=
    Html::activeDropDownList($model, 'tbl_xml_publisher_id_publisher', ArrayHelper::map(XmlPublisher::find()->orderBy('name ASC')->all(), 'id_publisher', 'name'), [
        'class' => 'form-control input-sm max-width-300',
        'prompt' => 'Select a publisher',
        'id' => 'publisher-select'
    ]);
    ?>
    <?= Html::error($model, 'tbl_xml_publisher_id_publisher', ['class' => 'help-block errorMessage']); ?>
</div>

<div class="form-group">
    <label>Segment</label>
    <?=
    Html::activeDropDownList($model, 'tbl_xml_segment_id_segment', $segmentsItems, [
        'class' => 'form-control input-sm max-width-300',
        'id' => 'segment-select',
        'disabled' => count($segmentsItems) > 0 ? false : true,
        'prompt' => 'Select a segment'
    ]);
    ?>
    <?= Html::error($model, 'tbl_xml_segment_id_segment', ['class' => 'help-block errorMessage']); ?>
</div>
<div class="form-group">
    <label>Status</label>
    <?=
    Html::activeDropDownList($model, 'enabled', [
        1 => 'Enabled',
        0 => 'Disabled'
            ], [
        'class' => 'form-control input-sm max-width-300',
        'prompt' => 'Select a status'
    ]);
    ?>
    <?= Html::error($model, 'enabled', ['class' => 'help-block errorMessage']); ?>
</div>
<div class="form-group clearfix">
    <div class="pull-right">
        <a href="<?= $queryString ?>" class="btn btn-default btn-lg">CANCEL</a>
        <input type="submit" class="btn btn-primary btn-lg" value="SAVE">
    </div>
</div>

<?php
ActiveForm::end();
?>