<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->registerJsFile(Url::base() . '/manager/js/maskedInput/jquery.maskedinput.min.js', ['position' => $this::POS_END, 'depends' => 'app\assets\ManagerAsset']);
$this->registerJs("
    $('.phoneMask').mask('(999) 999-9999');
");

$publisher = Yii::$app->request->get('publisher', null);
if (!empty($publisher)) {
    $model->tbl_media_publisher_id_media_publisher = $publisher;
}

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
    <h3 class="custom-style1"><?php echo ucfirst($model->first_name) . ' ' . ucfirst($model->last_name) ?></h3>
    <?php
}
?>
<div class="form-group">
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'first_name')->textInput(); ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'last_name')->textInput(); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-6">
            <?php echo $form->field($model, 'tbl_media_publisher_id_media_publisher')->dropDownList(ArrayHelper::map(\app\models\MediaPublisher::find()->orderBy('name ASC')->all(), 'id_media_publisher', 'name'), ['prompt' => 'Select a Publisher']); ?>
        </div>
        <div class="col-sm-6">
            <?php
            echo $form->field($model, 'time_zone')->dropDownList([
                'PST' => 'PST',
                'CST' => 'CST',
                'EST' => 'EST'
                    ], ['prompt' => 'Select a Time Zone']);
            ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'title')->textInput(); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'phone')->textInput(['class' => 'form-control phoneMask']); ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'ext')->textInput(); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'mobile')->textInput(['class' => 'form-control phoneMask']); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'email')->input('email'); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'notes')->textarea(['rows' => 7]); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'imageFile')->fileInput() ?>
            <?php app\components\Utils::getAvatarImage($model->photo, '200px', 50) ?>
        </div>
    </div>
</div>
<div class="form-group clearfix">
    <div class="pull-right">
        <a href="<?= Url::to(['contact/index']); ?>" class="btn btn-default btn-lg">CANCEL</a>
        <input type="submit" class="btn btn-primary btn-lg" value="SAVE">
    </div>
</div>


<?php
ActiveForm::end();
