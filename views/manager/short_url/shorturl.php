<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<section class="content-header clearfix">
    <h1 class="pull-left">
        <span>Manager</span> > <span>Google URL Shortner</span>
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <p>Paste all the final/real URL here, separete each of them by a line</p>
                    <?php
                    $form = ActiveForm::begin(['id' => 'form', 'class' => 'form-horizontal']);
                    ?>
                    <div class="form-group clearfix">
                        <div class="col-md-12">
                            <?= Html::activeTextarea($model, 'real_url', ['class' => 'form-control input-sm', 'style' => 'min-height: 300px;']); ?>
                            <?= Html::error($model, 'real_url', ['class' => 'help-block errorMessage']); ?>
                            <br>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>

                    <?php
                    if (!empty($table)) {
                        echo $table;
                    }

                    if (!empty($error)) {
                        echo "<span class=\"errorMessage\">$error</span>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>