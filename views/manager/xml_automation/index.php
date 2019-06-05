<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<section class="content-header clearfix">
  <h1 class="pull-left">
    <span>Manager</span> > <span>XML Automation</span>
  </h1>
</section>

<div class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-body">
            <? $form = ActiveForm::begin(['id' => 'form', 'class' => 'form-horizontal']);
            ?>

          <div class="form-group" style="max-width: 200px;">
            <label>Select a publisher</label>
            <?=
            Html::activeDropDownList($model, 'publisher', [
                'fatj' => 'FATJ',
                'simplyhired' => 'Simply Hired',
                'indeed' => 'Indeed'
                    ], [
                'prompt' => '--', 'class' => 'form-control input-sm'
            ]);
            ?>
            <?= Html::error($model, 'publisher'); ?>
          </div>
          <div class="form-group clearfix">
            <input type="submit" class="btn btn-primary" value="CONTINUE">
          </div>
          <?php
          ActiveForm::end();
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
