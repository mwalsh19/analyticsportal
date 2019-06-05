<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Url;

$this->title = 'Invalid Token';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="form-box" id="login-box">
    <div class="header" style="background-color: #2e2e2e;"><h2>Oops!</h2></div>
    <div class="alert alert-danger" role="alert">
        <center>
            <h3>The token is invalid.</h3>
        </center>
    </div>
</div>

<div style="margin: 75px auto 0px auto; max-width: 705px;">
    <img src="<?php echo Url::base(); ?>/manager/images/laced_footer.png">
</div>

