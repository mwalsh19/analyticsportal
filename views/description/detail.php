<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

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
                if(data.segments && data.segments != ''){
                    segmentDrop.html(data.segments);
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
?>
<section class = "content-header clearfix">
    <h1 class = "pull-left">
        <span>Manager</span> > <span>Description</span>
    </h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="form-group">
                                <h4>Description</h4>
                                <div class="well">
                                    <?php echo $model->description ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <a href="<?= $queryString; ?>" class="btn btn-default">BACK</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
