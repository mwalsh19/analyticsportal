<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->registerJsFile(Url::base() . '/manager/js/maskedInput/jquery.maskedinput.min.js', ['position' => $this::POS_END, 'depends' => 'app\assets\ManagerAsset']);
$this->registerJs("
//    $('.phoneMask').mask('(999) 999-9999');
");

$form = ActiveForm::begin([
            'id' => 'form',
            'options' => [
                'enctype' => 'multipart/form-data'
            ]
        ]);
?>
<div class="box">
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <h3>Campaign Name</h3><br>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <?= $form->field($model, 'campaign_name')->textInput(); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <?php echo $form->field($model, 'attn')->textInput(); ?>
                        </div>
                        <div class="col-sm-6">
                            <label>Date:</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <?= yii\helpers\Html::activeTextInput($model, 'date', ['class' => 'form-control pull-right']); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'io_number')->textInput(); ?>
                        </div>
                        <div class="col-sm-3">
                            <?= $form->field($model, 'total_net')->textInput(['class' => 'form-control', 'placeholder' => '$00.00']); ?>
                        </div>
                        <div class="col-sm-3">
                            <?= $form->field($model, 'total_gross')->textInput(['class' => 'form-control', 'placeholder' => '$00.00']); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <?= $form->field($model, 'terms')->textInput(); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <?= $form->field($model, 'overview')->textarea(['rows' => 7]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="box">
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <h3>Fields</h3><br>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <strong>Total Budget</strong>
                        </div>
                        <div class="col-sm-3">
                            <div style="font-size: 16px;"><strong>Net:</strong><span class="text-custom-color"><strong>$00</strong>.00</span></div>
                        </div>
                        <div class="col-sm-3">
                            <div style="font-size: 16px;"><strong>Net:</strong><span class="text-custom-color"><strong>$00</strong>.00</span></div>
                        </div>
                    </div>
                </div>
                <hr style="margin-top: 0;border-color: #000;">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pull-right">
                                <a href="" class="btn btn-primary btn-custom">Add Field <i class="glyphicon glyphicon-plus"></i></a>
                            </div>
                            <div class="clearfix fields-container" style="min-height: 100px;">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <strong>Total Budget</strong>
                        </div>
                        <div class="col-sm-3">
                            <div style="font-size: 16px;"><strong>Net:</strong><span class="text-custom-color"><strong>$00</strong>.00</span></div>
                        </div>
                        <div class="col-sm-3">
                            <div style="font-size: 16px;"><strong>Net:</strong><span class="text-custom-color"><strong>$00</strong>.00</span></div>
                        </div>
                    </div>
                </div>
                <hr style="margin-top: 0;border-color: #000;">
            </div>
        </div>
    </div>
</div>

<div class="form-group clearfix">
    <div class="pull-right">
        <a href="<?= Url::to(['insertion-order/index']); ?>" class="btn btn-default btn-lg">CANCEL</a>
        <input type="submit" class="btn btn-primary btn-lg" value="SAVE">
    </div>
</div>


<?php
ActiveForm::end();
