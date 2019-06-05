<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Company;

$form = ActiveForm::begin([
            'id' => 'form',
            'options' => [
            ]
        ]);
?>

<div class="form-group">
    <?= Html::activeLabel($model, 'name', ['class' => 'control-label']) ?>
    <?= Html::activeTextInput($model, 'name', ['class' => 'form-control input-sm max-width-300']); ?>
    <?= Html::error($model, 'name', ['class' => 'help-block errorMessage']); ?>
</div>
<div class="form-group">
    <label>Company</label>
    <?=
    Html::activeDropDownList($model, 'tbl_company_id_company', ArrayHelper::map(Company::find()->orderBy('name ASC')->all(), 'id_company', 'name'), [
        'class' => 'form-control input-sm max-width-300',
        'prompt' => 'Select a company'
    ]);
    ?>
    <?= Html::error($model, 'tbl_company_id_company', ['class' => 'help-block errorMessage']); ?>
</div>
<div class="form-group clearfix">
    <div class="pull-right">
        <a href="<?= Url::to(['publisher/index']); ?>" class="btn btn-default btn-lg">CANCEL</a>
        <input type="submit" class="btn btn-primary btn-lg" value="SAVE">
    </div>
</div>
<?php
ActiveForm::end();
