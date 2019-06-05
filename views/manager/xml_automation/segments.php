<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->registerJs('
        $(document).on("click", ".collapse-panel-btn", function(event){
        event.preventDefault();
        var target = $(this).data("target"),
        elemBody = $("#"+target+" .panel-body"),
        icon = $(this).find("i");

        elemBody.toggleClass("collapse");

        if(elemBody.hasClass("collapse")){
            icon.removeClass("glyphicon-minus");
            icon.addClass("glyphicon-plus");
        }else{
            icon.removeClass("glyphicon-plus");
            icon.addClass("glyphicon-minus");
        }
    });

    $(\'input[type="checkbox"][name="tooglePanels"]\').on(\'ifChecked\', function(event){
        $(".cityStatesContainer .panel-body").addClass("collapse");
    });
    $(\'input[type="checkbox"][name="tooglePanels"]\').on(\'ifUnchecked\', function(event){
         $(".cityStatesContainer .panel-body").removeClass("collapse");
    });

');
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
            <?php if (Yii::$app->session->hasFlash('errorMsg')) {
                ?>
              <div class="alert alert-danger" role="alert"><?= Yii::$app->session->getFlash('errorMsg'); ?></div>
          <?php } ?>
          <?php
          $form = ActiveForm::begin(['id' => 'form', 'class' => 'form-horizontal', 'action' => Url::to(['manager/xml-automation-segments'])]);
          ?>
          <div class="form-group" style="text-align: center;">
            <h4><strong>Segment</strong>: <?php echo $segment; ?></h4>
          </div>
          <div class="form-group clearfix">
            <div class="col-sm-3">
              <label>Loops count:</label>
              <?=
              Html::activeTextInput($tool_model, 'loop_count', ['class' => 'form-control input-sm',
                  'style' => 'max-width: 50px;'])
              ?>
              <div><?= Html::error($tool_model, 'loop_count', ['class' => 'label label-danger']) ?></div>
            </div>
            <div class="col-sm-9">
              <div class="pull-right">
                <label>Collapse all panels&nbsp;&nbsp;</label>
                <?= Html::checkbox('tooglePanels', true); ?>
              </div>
            </div>
          </div>
          <div class="form-group clearfix">
            <div class="cityStatesContainer">

              <?php
              if (!empty($cityStateList)) {
                  $currentState = '';

                  $unserializedData = '';
                  if (!empty($sessionStorage) && !empty($sessionStorage->payload)) {
                      $unserializedData = unserialize($sessionStorage->payload);
                  }

                  for ($index = 0; $index < count($cityStateList); $index++) {
                      $cityStateModel = $cityStateList[$index];
                      $state = $cityStateModel->state;
                      $city = $cityStateModel->city;

                      $stateChecked = '';
                      $citychecked = '';

                      if (!empty($unserializedData)) {
                          for ($index1 = 0; $index1 < count($unserializedData); $index1++) {
                              $matchval = $unserializedData[$index1];
                              if ($matchval == $state) {
                                  //for state
                                  $stateChecked = "checked='true'";
                              }
                              if ($matchval == $state . "_" . $city) {
                                  //for city
                                  $citychecked = "checked='true'";
                              }
                          }
                      }


                      if ($currentState != $state) {
                          if ($currentState != '') {
                              ?>
                            </div></div></div>
                        <?php
                    }
                    ?>
                    <div class="col-sm-4">
                      <div class="panel panel-default" id="panel_<?php echo $index; ?>">
                        <div class="panel-heading">
                          <input type='checkbox' name='city_state[]' value='<?php echo $state ?>' class='city_state_input' <?php echo $stateChecked; ?>>&nbsp;&nbsp;
                          <?php echo $state; ?>
                          <div class="pull-right">
                            <a href="#" data-target="panel_<?php echo $index; ?>" class="btn btn-danger btn-sm collapse-panel-btn">
                              <i class="glyphicon glyphicon-plus"></i>
                            </a>
                          </div>
                        </div>
                        <div class="panel-body collapse">
                          <label>
                            <input type='checkbox' name='city_state[]' value='<?php echo $state ?>_<?php echo $city ?>' class='city_state_input' <?php echo $citychecked; ?>>
                            <span>&nbsp;&nbsp;<?php echo $city ?></span>
                          </label>
                          <?php
                      } else {
                          ?>
                          <label>
                            <input type='checkbox' name='city_state[]' value='<?php echo $state ?>_<?php echo $city ?>' class='city_state_input' <?php echo $citychecked; ?>>
                            <span>&nbsp;&nbsp;<?php echo $city ?></span>
                          </label>
                          <?php
                      }

                      $currentState = $state;
                  }
              }
              ?>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-12">
              <?php if ($segmentPos >= 1) { ?>
            <a href="<?= Url::to(['manager/xml-automation-segments']); ?>?segment=<?= $segmentPos; ?>" class="btn btn-primary">BACK</a>&nbsp;&nbsp;&nbsp;
            <?php } ?>
            <input type="submit" class="btn btn-primary" value="CONTINUE">
          </div>
        </div>
        <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</div>
</div>
