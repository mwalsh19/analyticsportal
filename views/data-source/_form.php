<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Alert;

$basePath = Url::base();
$params = [
    'position' => yii\web\View::POS_END,
    'depends' => app\assets\ManagerAsset::className()
];

$this->registerCssFile('http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
$this->registerJsFile('http://code.jquery.com/ui/1.11.4/jquery-ui.js', $params);
$this->registerJsFile($basePath . '/manager/js/plugins/daterangepicker/moment.js', $params);
$this->registerJsFile($basePath . '/manager/js/plugins/daterangepicker/daterangepicker.js', $params);
$this->registerCssFile($basePath . '/manager/css/daterangepicker/daterangepicker-bs3.css');
$this->registerJsFile($basePath . '/manager/js/data-source-form.js', $params);

$session = Yii::$app->session;
if ($session->hasFlash('error')) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-danger',
        ],
        'body' => $session->get('error'),
    ]);
}
if ($session->hasFlash('success')) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-success',
        ],
        'body' => $session->get('success'),
    ]);
}

$form = ActiveForm::begin([
            'id' => 'form',
            'options' => [
                'enctype' => 'multipart/form-data'
            ]
        ]);

$isCallSource = app\models\DataSource::isCallSource();
?>

<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <div class="step1">
                <p>
                    <strong>Step 1: </strong>Please choose the date range of the file you are uploading.<br>
                    <br>
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm select-data-range-btn">Select date range</a>
                </p>
                <div class="date-labels" style="display: none;">
                    From: <strong class="from-text"></strong><br>
                    To: <strong class="to-text"></strong>
                </div>
            </div>
            <div class="step2" <?php echo!empty($model->from_hidden_field) && !empty($model->to_hidden_field) ? '' : 'style="display: none;"'; ?>>
                <hr>
                <p>
                    <strong>Step 2: </strong>Select the data source file from your computer.<br>
                </p>
                <?= $form->field($model, 'sourceFile')->fileInput(['id' => 'source-file']) ?>
                <?= \yii\helpers\Html::activeHiddenInput($model, 'from_hidden_field', ['id' => 'from-hidden']) ?>
                <?= \yii\helpers\Html::activeHiddenInput($model, 'to_hidden_field', ['id' => 'to-hidden']) ?>
            </div>
            <div class="step3" <?php echo!empty($model->from_hidden_field) && !empty($model->to_hidden_field) ? '' : 'style="display: none;"'; ?>>
                <hr>
                <p>
                    <strong>Step 3: </strong>Label the file<br>
                </p>
                <?= $form->field($model, 'label')->textInput(['id' => 'label-file']) ?>

                <?php
                if (!$isCallSource) {
                    ?>
                    <hr>
                    <p>
                        <strong>Step 4: </strong>Code type<br>
                    </p>
                    <?php
                    echo $form->field($model, 'code_type')->dropDownList([
                        'referrer' => 'Referrer',
                        'source' => 'Source'
                            ], [
                        'prompt' => '--Please select Code Type--',
                        'style' => 'max-width: 320px; ',
                    ]);
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="form-group clearfix actions" <?php echo!empty($model->label) ? '' : 'style="display: none;"'; ?>>
    <div class="pull-right">
        <!--<a href="<?php // Url::to(['data-source/index', 'type' => $isCallSource ? 'call' : 'data']);  ?>" class="btn btn-default btn-lg">CANCEL</a>-->
        <input type="submit" class="btn btn-primary btn-lg" value="SAVE">
    </div>
</div>

<?php
ActiveForm::end();
?>

<!-- Modal -->
<div class="modal fade" id="calendarModal" tabindex="-1" role="dialog" aria-labelledby="calendarModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p>
                    Dates:
                    <label><b>From:</b></label>
                    <input type="text" id="input1" size="20">&nbsp;&nbsp;
                    <label><b>To:</b></label>
                    <input type="text" id="input2" size="20">
                </p>
                <div class="clearfix">
                    <div id="from" style="float: left; width: 50%;"></div>
                    <div id="to" style="float: left; width: 50%;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-primary btn-sm apply-date-range">Apply</a>
            </div>
        </div>
    </div>
</div>