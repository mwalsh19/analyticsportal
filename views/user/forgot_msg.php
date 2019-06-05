<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Url;

$this->title = 'Forgot Password Status';
$this->params['breadcrumbs'][] = $this->title;
$status = Yii::$app->request->get('status', '');
?>

<div class="form-box" id="login-box">
    <div class="header" style="background-color: #2e2e2e;"><h2>Your password has changed</h2></div>
    <div class="alert alert-success" role="alert">
        <center>
            <p>Please login with your new password.</p>
            <hr>
            <p>
                <a href="<?php echo Url::to(['user/login']) ?>" class="btn btn-primary btn-lg">Login</a>
            </p>
        </center>
    </div>
</div>

<div style="margin: 75px auto 0px auto; max-width: 705px;">
    <img src="<?php echo Url::base(); ?>/manager/images/laced_footer.png">
</div>

