<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Alert;

$this->registerJs('
    var panelsBody =  $(".cityStatesContainer .panel-body");

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

    $(\'input[type="checkbox"][name="tooglePanels"]\').on("click", function(event){
        if(!$(this).is(":checked")){
            $(".cityStatesContainer .panel-body").removeClass("collapse");
            $(".cityStatesContainer .collapse-panel-btn i").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }else{
            $(".cityStatesContainer .panel-body").addClass("collapse");
            $(".cityStatesContainer .collapse-panel-btn i").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        }
    });

 $(\'input[type="checkbox"][name="unCheckAll"]\').on("click", function(event){
        var checkAll = false,
            msg = "uncheck";

        if($(this).is(":checked")){
            checkAll = true;
            msg = "check"
        }

        if(window.confirm("Are your sure you want "+msg+" all City States?  this operation can\'t be revert.")){
            $(".city_state_input").prop("checked", checkAll);
        }else{
            event.preventDefault();
        }

    });

');

$isJibe = false;
if (strpos(strtolower($publisherObject->name), 'jibe') !== false) {
    $isJibe = true;
}
?>

<section class="content-header">
    <h1>
        <span>Manager</span> > <span>XML Automation</span>
    </h1>
    <?php
    $session = Yii::$app->session;
    if ($session->hasFlash('success')) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success',
                'style' => 'margin: 0; margin-top: 10px;'
            ],
            'body' => $session->getFlash('success'),
        ]);
    }
    if ($session->hasFlash('fail')) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-danger',
                'style' => 'margin: 0; margin-top: 10px;'
            ],
            'body' => $session->getFlash('fail'),
        ]);
    }
    ?>
</section>

<div class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <?php
                    $form = ActiveForm::begin(['id' => 'form', 'class' => 'form-horizontal', 'action' => Url::to(['xml-automation/segments'])]);
                    ?>
                    <div class="form-group clearfix">
                        <div class="col-sm-5">
                            <div class="well">
                                <h4>
                                    <strong>Publisher</strong>: <?php echo $publisherObject->name; ?><br>
                                    <strong>Segment</strong>: <?php echo $segment->name; ?>
                                </h4>
                                <div>
                                    <label>Loops count:</label>
                                    <?=
                                    Html::activeTextInput($tool_model, 'loop_count', ['class' => 'form-control input-sm',
                                        'style' => 'max-width: 50px;'])
                                    ?>
                                    <div><?= Html::error($tool_model, 'loop_count', ['class' => 'label label-danger']) ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="pull-right">
                                <div class="well">
                                    <label>Collapse all panels&nbsp;&nbsp;</label>
                                    <?= Html::checkbox('tooglePanels', true); ?>
                                    <br>
                                    <label>Check/unckeck all city states</label>
                                    <?= Html::checkbox('unCheckAll', false); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-12">
                            <?php if ($segmentPos < 1) { ?>
                                <a href="<?= Url::to(['xml-automation/review']); ?>" class="btn btn-default btn-sm">PREVIOUS PAGE</a>&nbsp;&nbsp;&nbsp;
                            <?php } ?>
                            <?php if ($segmentPos >= 1) { ?>
                                <a href="<?= Url::to(['xml-automation/segments']); ?>?segment=<?= $segmentPos; ?>" class="btn btn-primary btn-sm">BACK</a>&nbsp;&nbsp;&nbsp;
                            <?php } ?>
                            <input type="submit" class="btn btn-primary btn-sm" value="CONTINUE">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group clearfix">
                        <div class="cityStatesContainer clearfix">

                            <?php
                            if (!empty($cityStateList)) {
                                $currentState = '';

                                $unserializedData = '';
                                if (!empty($sessionStorage) && !empty($sessionStorage->payload)) {
                                    $unserializedData = unserialize($sessionStorage->payload);
                                }

                                $stateHasSelectedCities = false;

                                for ($index = 0; $index < count($cityStateList); $index++) {
                                    $cityStateModel = $cityStateList[$index];
                                    $state = trim($cityStateModel['state']);

                                    if ($currentState != $state) {
                                        $stateHasSelectedCities = false;
                                    }

                                    $city = trim(ucwords(strtolower($cityStateModel['city'])));
                                    $jibe_code = 'na';

                                    if ($isJibe && !empty($cityStateModel['craigslist_market'])) {
                                        $jibe_code = $cityStateModel['craigslist_market'];
                                    }

                                    $stateChecked = '';
                                    $citychecked = '';

                                    //var_dump($unserializedData);
                                    //die();

                                    if (!empty($unserializedData)) {
                                        for ($index1 = 0; $index1 < count($unserializedData); $index1++) {
                                            $matchval = $unserializedData[$index1];

                                            $justState = explode('_', $matchval);
                                            //var_dump($justState);
                                            if ($justState[0] == $state) {
                                                $stateHasSelectedCities = true;
                                            }

                                            if ($matchval == $state) {
                                                //for state
                                                $stateChecked = "checked='true'";
                                            }
                                            if ($matchval == $state . "_" . $city . "_" . $jibe_code) {
                                                //for city
                                                $citychecked = "checked='true'";
                                            }
                                        }
                                    }


                                    if ($currentState != $state) {

                                        if ($currentState != '') {
                                            echo " </div></div></div>";
                                        }
                                        ?>
                                        <div class="col-sm-4">
                                            <div class="panel panel-default" id="panel_<?php echo $index; ?>">
                                                <div class="panel-heading">
                                                    <?php
                                                    if (!$isJibe) {
                                                        ?>
                                                        <input type='checkbox' name='city_state[]' value='<?php echo $state ?>' class='city_state_input' <?php echo $stateChecked; ?>>&nbsp;&nbsp;
                                                        <?php
                                                    }
                                                    ?>

                                                    <?php echo $state; ?>
                                                    <div class="pull-right">
                                                        <a href="#" data-target="panel_<?php echo $index; ?>" class="btn btn-danger btn-sm collapse-panel-btn">
                                                            <i class="glyphicon <?php echo $stateHasSelectedCities ? 'glyphicon-minus' : 'glyphicon-plus' ?>"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="panel-body <?php echo $stateHasSelectedCities ? '' : 'collapse'; ?>">
                                                    <label>
                                                        <input type='checkbox' name='city_state[]' value="<?php echo "{$state}_{$city}_{$jibe_code}" ?>" class='city_state_input' <?php echo $citychecked; ?>>
                                                        <span>&nbsp;&nbsp;<?php echo $city ?></span>
                                                    </label>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <label>
                                                        <input type='checkbox' name='city_state[]' value="<?php echo "{$state}_{$city}_{$jibe_code}" ?>" class='city_state_input' <?php echo $citychecked; ?>>
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
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <?php if ($segmentPos < 1) { ?>
                                    <a href="<?= Url::to(['xml-automation/review']); ?>" class="btn btn-default btn-sm">PREVIOUS PAGE</a>&nbsp;&nbsp;&nbsp;
                                <?php } ?>
                                <?php if ($segmentPos >= 1) { ?>
                                    <a href="<?= Url::to(['xml-automation/segments']); ?>?segment=<?= $segmentPos; ?>" class="btn btn-primary btn-sm">BACK</a>&nbsp;&nbsp;&nbsp;
                                <?php } ?>
                                <input type="submit" class="btn btn-primary btn-sm" value="CONTINUE">
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
