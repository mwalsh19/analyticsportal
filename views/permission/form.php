<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;
?>
<section class = "content-header clearfix">
    <h1>
        <span>Manager > </span><span class="blue-span"><a href="<?php echo Url::to(['company/index']); ?>">Permission Overview</a> > </span><span>Add Permission</span>
    </h1>
    <?php
    $session = Yii::$app->session;
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
    <?php
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
<section class="content" id="<?php echo Yii::$app->controller->id ?>">
    <div class="row">
        <div class="col-xs-10">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php
                            $form = ActiveForm::begin([
                                        'id' => 'form'
                            ]);
                            ?>
                            <div class="form-group">
                                <div class="max-width-300">
                                    <?= $form->field($model, 'permission_name')->textInput(); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="max-width-300">
                                    <?= $form->field($model, 'permission_description')->textarea() ?>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <div class="pull-right">
                                    <a href="<?= Url::to(['permission/index']); ?>" class="btn btn-default btn-lg">CANCEL</a>
                                    <input type="submit" class="btn btn-primary btn-lg" value="SAVE">
                                </div>
                            </div>

                            <?php
                            ActiveForm::end();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
