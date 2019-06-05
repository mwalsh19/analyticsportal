<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Alert;

$this->title = 'Forgot Password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="form-box" id="login-box">
    <div class="header" style="background-color: #2e2e2e;">
        <h2>Reset Password</h2>
    </div>

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
                <?= Html::activeTextInput($model, 'email', ['placeholder' => 'Your email', 'class' => 'form-control']) ?>
                <?= Html::error($model, 'email', ['class' => 'label label-danger']); ?>
            </div>
        </div>
    </div>
    <div class="footer">
        <?= Html::submitButton('Reset Password', ['class' => 'btn btn-default btn-block', 'name' => 'change-button']) ?>
        <br>
        <a href="<?php echo Url::to(['user/login']) ?>" style="color: #cd3303;font-size: 15px;"><i class="fa fa-arrow-left"></i> Back to login</a>
    </div>
    <?php ActiveForm::end(); ?>

    <?php
    $session = Yii::$app->session;
    if ($session->hasFlash('error')) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-danger',
                'style' => 'margin: 0; margin-top: 10px;'
            ],
            'body' => $session->get('error'),
        ]);
    }
    if ($session->hasFlash('success')) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success',
                'style' => 'margin: 0; margin-top: 10px;'
            ],
            'body' => $session->get('success'),
        ]);
    }
    ?>
</div>

<div style="margin: 75px auto 0px auto; max-width: 705px;">
    <img src="<?php echo Url::base(); ?>/manager/images/laced_footer.png">
</div>

