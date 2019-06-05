<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->registerJsFile(Url::base() . '/manager/js/maskedInput/jquery.maskedinput.min.js', ['position' => $this::POS_END,
    'depends' => 'app\assets\ManagerAsset']);
$this->registerJs("
    $('.phoneMask').mask('(999) 999-9999');
");
$basePath = Url::base();
$form = ActiveForm::begin([
            'id' => 'form',
            'options' => [
                'enctype' => 'multipart/form-data'
            ]
        ]);
?>
<?php
if (!$model->isNewRecord) {
    ?>
    <h3 class="custom-style1"><?php echo ucfirst($model->name); ?></h3>
    <?php
}
?>
<div class="form-group">
  <div class="row">
    <div class="col-sm-12">
        <?= $form->field($model, 'name')->textInput(); ?>
    </div>
  </div>
</div>
<div class="form-group">
  <div class="row">
    <div class="col-sm-12">
        <?= $form->field($model, 'address')->textarea(['rows' => 2]); ?>
    </div>
  </div>
</div>
<div class="form-group">
  <div class="row">
    <div class="col-sm-4">
        <?= $form->field($model, 'city')->textInput(); ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'state')->dropDownList(app\components\Utils::getStateArray(), ['prompt' => 'Select State']); ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'zip_code')->textInput(); ?>
    </div>
  </div>
</div>
<div class="form-group">
  <div class="row">
    <div class="col-sm-12">
        <?= $form->field($model, 'phone_number')->textInput(['class' => 'form-control phoneMask']); ?>
    </div>
  </div>
</div>
<div class="form-group">
  <div class="row">
    <div class="col-sm-12">
        <?= $form->field($model, 'website_url')->textInput(); ?>
    </div>
  </div>
</div>
<div class="form-group">
  <div class="row">
    <div class="col-sm-6">
        <?= $form->field($model, 'tenstreet_referrer_part')->textInput(); ?>
    </div>
  </div>
</div>
<div class="form-group">
  <div class="row">
    <div class="col-sm-12">
        <?= $form->field($model, 'imageFile')->fileInput() ?>
        <?php
        if (!empty($model->logo)) {
            ?>
          <img src="<?php echo $basePath . '/uploads/publisher_logo/' . $model->logo ?>" class="img-rounded" width="200">
          <?php
      }
      ?>
    </div>
  </div>
</div>

<div class="form-group clearfix">
  <div class="pull-right">
    <a href="<?= Url::to(['media-publisher/index']); ?>" class="btn btn-default btn-lg">CANCEL</a>
    <input type="submit" class="btn btn-primary btn-lg" value="SAVE">
  </div>
</div>


<?php
ActiveForm::end();
?>

