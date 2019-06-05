<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\XmlPublisher;
use yii\helpers\ArrayHelper;

$this->registerJs(
        "
    var id_segment = $('.id_segment'),
    isChecked;
    $('.publisher_list input').on('click', function(){
        if($(this).is(':checked')){
            isChecked = 'Y';
        }
        if(!$(this).is(':checked')){
             isChecked = 'N';
        }
        var params =  {id_publisher: $(this).val(), id_segment:id_segment.val(), isChecked: isChecked};
        $.post('publisher-change',params, function(response){
            console.log(response);
        }).fail(function(){

        });
    });
"
);

$form = ActiveForm::begin([
            'id' => 'form'
        ]);
?>


<div class="form-group">
    <?= Html::activeLabel($model, 'name', ['class' => 'control-label']) ?>
    <?= Html::activeTextInput($model, 'name', ['class' => 'form-control input-sm max-width-300']); ?>
    <?= Html::hiddenInput('id_segment', $model->id_segment, ['class' => 'id_segment']); ?>
    <?= Html::error($model, 'name', ['class' => 'help-block errorMessage']); ?>
</div>
<div class="form-group">
    <div class="panel panel-default" id="segment-campaigns">
        <div class="panel-heading">
            <h4 class="panel-title">Publishers</h4>
        </div>
        <div class="panel-body">
            <?=
            Html::activeCheckboxList($model, 'publishers', ArrayHelper::map(XmlPublisher::find()->orderBy('name ASC')->all(), 'id_publisher', 'name'), ['class' => 'publisher_list'])
            ?>
            <?= Html::error($model, 'publishers', ['class' => 'help-block errorMessage']); ?>
        </div>
    </div>
</div>

<div class="form-group clearfix">
    <div class="pull-right">
        <a href="<?= Url::to(['segment/index']); ?>" class="btn btn-default btn-lg">CANCEL</a>
        <input type="submit" class="btn btn-primary btn-lg" value="SAVE">
    </div>
</div>

<?php
ActiveForm::end();
