<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Alert;

$this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="form-box" id="login-box">
    <div class="header" style="background-color: #2e2e2e;">
        <h2>Change Password</h2>
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
                <?= Html::activePasswordInput($model, 'new_password', ['placeholder' => 'Enter new password', 'class' => 'form-control']) ?>
                <?= Html::error($model, 'new_password', ['class' => 'label label-danger']); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?= Html::activePasswordInput($model, 'repeat_new_password', ['placeholder' => 'Repeat new password', 'class' => 'form-control']) ?>
                <?= Html::error($model, 'repeat_new_password', ['class' => 'label label-danger']); ?>
            </div>
        </div>
    </div>
    <div class="footer">
        <?= Html::submitButton('Change Password', ['class' => 'btn btn-default btn-block', 'name' => 'change-button']) ?>
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

