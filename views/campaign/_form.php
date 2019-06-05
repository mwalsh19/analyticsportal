<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$form = ActiveForm::begin([
            'id' => 'form'
        ]);
?>

<div class="form-group">
    <?= Html::activeLabel($model, 'name', ['class' => 'control-label']) ?>
    <?= Html::activeTextInput($model, 'name', ['class' => 'form-control input-sm max-width-300']); ?>
    <?= Html::error($model, 'name', ['class' => 'help-block errorMessage']); ?>
</div>
<div class="form-group">
    <?= Html::activeLabel($model, 'status', ['class' => 'control-label']) ?>
    <?=
    Html::activeDropDownList($model, 'status', [1 => 'Enabled', 0 => 'Disabled'], [
        'class' => 'form-control input-sm max-width-300',
        'prompt' => 'Select a status'
    ]);
    ?>
    <?= Html::error($model, 'status', ['class' => 'help-block errorMessage']); ?>
</div>
<div class="form-group clearfix">
    <div class="pull-right">
        <a href="<?= Url::to(['campaign/index']); ?>" class="btn btn-default btn-lg">CANCEL</a>
        <input type="submit" class="btn btn-primary btn-lg" value="SAVE">
    </div>
</div>

<?php
ActiveForm::end();
