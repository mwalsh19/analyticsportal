<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="form-box" id="login-box">
    <div class="header" style="background-color: #2e2e2e;"><h2>Welcome</h2></div>

    <?php
    $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => [
                    'class' => 'form-horizontal',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => true,
                ]
    ]);
    ?>

    <div class="body bg-gray">
        <div class="form-group">
            <div class="col-sm-12">
                <?= Html::activeTextInput($model, 'username', ['placeholder' => 'User ID', 'class' => 'form-control']) ?>
                <?= Html::error($model, 'username', ['class' => 'label label-danger']); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?= Html::activePasswordInput($model, 'password', ['placeholder' => 'Password', 'class' => 'form-control']) ?>
                <?= Html::error($model, 'password', ['class' => 'label label-danger']); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?php
                echo Html::activeCheckbox($model, 'rememberMe', [
                    'template' => "{input}&nbsp;&nbsp;{label}<div class='label label-danger'>{error}</div>",
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="footer">
        <?= Html::submitButton('Sign me in', ['class' => 'btn btn-default btn-block', 'name' => 'login-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<div style="margin: 75px auto 0px auto; max-width: 705px;">
    <img src="<?php echo Url::base(); ?>/manager/images/laced_footer.png">
</div>

