<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\bootstrap\Alert;
?>
<section class = "content-header clearfix">
    <h1>
        <span>Manager > </span><span class="blue-span"><a href="<?php echo Url::to(['user/index']); ?>">Users Overview</a> > </span><span>Add User</span>
    </h1>
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
    ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-10">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php
                            echo $this->render('_form', ['model' => $model, 'roles' => $roles]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
