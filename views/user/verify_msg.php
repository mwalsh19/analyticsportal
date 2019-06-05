<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Url;

$this->title = 'Verify Account';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="form-box" id="login-box">
    <?php
    if (!empty($model->token)) {
        ?>
        <div class="header" style="background-color: #2e2e2e;"><h2>You account is now active!</h2></div>
        <div class="alert alert-success" role="alert">
            <center>
                <p>Please login using the temporary password we provided in the email, you will be asked to changed after your first login for security reasons</p>
                <hr>
                <p>
                    <a href="<?php echo Url::to(['user/login', 'token' => $model->token]) ?>" class="btn btn-primary btn-lg">Login</a>
                </p>
            </center>
        </div>
        <?php
    } else {
        ?>
        <div class="header" style="background-color: #2e2e2e;"><h2>Oops!</h2></div>
        <div class="alert alert-danger" role="alert">
            <center>
                <h3>The token is invalid.</h3>
            </center>
        </div>
        <?php
    }
    ?>
</div>

<div style="margin: 75px auto 0px auto; max-width: 705px;">
    <img src="<?php echo Url::base(); ?>/manager/images/laced_footer.png">
</div>

