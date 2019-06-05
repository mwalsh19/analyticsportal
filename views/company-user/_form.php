<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$basePath = Url::base();

$form = ActiveForm::begin([
            'id' => 'form',
            'options' => [
                'enctype' => 'multipart/form-data'
            ]
        ]);
?>

<div class="form-group">
    <?= Html::activeLabel($model, 'name', ['class' => 'control-label']) ?>
    <?= Html::activeTextInput($model, 'name', ['class' => 'form-control input-sm max-width-300']); ?>
    <?= Html::error($model, 'name', ['class' => 'help-block errorMessage']); ?>
</div>
<div class="form-group">
    <?= Html::activeLabel($model, 'tenstreet_company_id', ['class' => 'control-label']) ?>
    <?= Html::activeTextInput($model, 'tenstreet_company_id', ['class' => 'form-control input-sm max-width-300']); ?>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'imageFile')->fileInput() ?>
            <?php
            if (!empty($model->logo)) {
                ?>
                <img src="<?php echo $basePath . '/uploads/company_logo/' . $model->logo ?>" class="img-rounded">
                <?php
            }
            ?>
        </div>
    </div>
</div>
<div class="form-group clearfix">
    <div class="pull-right">
        <a href="<?= Url::to(['company-user/index']); ?>" class="btn btn-default btn-lg">CANCEL</a>
        <input type="submit" class="btn btn-primary btn-lg" value="SAVE">
    </div>
</div>

<?php
ActiveForm::end();
