<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Company;
use yii\bootstrap\Alert;
use app\models\XmlPublisher;
use yii\helpers\Url;

$this->registerJsFile(Url::base() . '/manager/js/xml-automation.js', ['position' => $this::POS_END, 'depends' => 'app\assets\ManagerAsset']);
$this->registerJsFile(Url::base() . '/manager/js/maskedInput/jquery.maskedinput.min.js', ['position' => $this::POS_END, 'depends' => 'app\assets\ManagerAsset']);
$this->registerJs("
    $('#phone').mask('(999) 999-9999');
");

$publishers = [];
if (!empty($model->company)) {
    $publishers = ArrayHelper::map(XmlPublisher::find()->where('tbl_company_id_company=:id_company', [
                        ':id_company' => $model->company])->all(), 'id_publisher', 'name');
}


$segments = [];
if (!empty($model->publisher)) {
    $publisher = XmlPublisher::find()->where('id_publisher=:id_publisher', [':id_publisher' => $model->publisher])->with('tblXmlSegmentIdSegments')->one();
    $segments = ArrayHelper::map($publisher->tblXmlSegmentIdSegments, 'id_segment', 'name');
}
?>

<section class="content-header">
    <h1>
        <span>XML Generator</span> > <span>Review</span>
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
                    \yii\widgets\ActiveForm::begin(['id' => 'review-form']);
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <p>
                                Select the Company, Publisher and Segment you'd  like to review<br>
                                Is the information you'd like to use displayed below?
                            </p>
                            <p>You can edit the Job Titles, Description or URL before it's final.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <?=
                            Html::activeDropDownList($model, 'company', ArrayHelper::map(Company::find()->all(), 'id_company', 'name'), [
                                'class' => 'form-control',
                                'id' => 'company-select',
                                'prompt' => 'SELECT COMPANY'
                            ])
                            ?>
                        </div>
                        <div class="col-sm-2">
                            <?=
                            Html::activeDropDownList($model, 'publisher', $publishers, [
                                'class' => 'form-control',
                                'id' => 'publisher-select',
                                'prompt' => count($publishers) > 0 ? 'SELECT PUBLISHER' : '',
                                'disabled' => count($publishers) > 0 ? false : true
                            ])
                            ?>
                        </div>
                        <div class="col-sm-2">
                            <?=
                            Html::activeDropDownList($model, 'segment', $segments, [
                                'class' => 'form-control',
                                'id' => 'segment-select',
                                'prompt' => count($segments) > 0 ? 'SELECT SEGMENT' : '',
                                'disabled' => count($segments) > 0 ? false : true
                            ])
                            ?>
                        </div>
                    </div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Titles</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="title-container">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Descriptions</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="description-container">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Urls</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="url-container">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title" style="margin-top: 6px;">Phone</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="phone-container">
                                        <input type="text" name="phone" class="form-control" id="phone" style="max-width: 300px;">
                                    </div>
                                </div>
                                <div class="panel-footer hide has-phone">
                                    <button class="btn btn-primary" id="save-phone">Save</button>
                                    <img src="<?= Url::base(); ?>/manager/images/loading.gif" class="loading-img two hide">
                                    <span class="text-success message-status2"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div>
                                        <label>XML layout (Root)</label>
                                        <input type="text" id="xml-root" class="form-control" style="max-width: 300px;">
                                    </div>
                                    <br>
                                    <div>
                                        <label>XML layout (Extra Fields)</label>
                                        <textarea id="extra-fields" class="form-control"  rows="7"></textarea>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <button class="btn btn-primary" id="save-xml-fields">Save</button>
                                    <img src="<?= Url::base(); ?>/manager/images/loading.gif" class="loading-img three hide">
                                    <span class="text-success message-status3"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title" style="margin-top: 6px;">Xml Layout (Job Layout)</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="xml-layout-container">
                                        <textarea name="xml-layout" class="form-control" id="xml-layout" placeholder="Xml Job layout here..." rows="13"></textarea>
                                    </div>
                                </div>
                                <div class="panel-footer has-xml-layout">
                                    <button class="btn btn-primary" id="save-xml-layout">Save</button>
                                    <img src="<?= Url::base(); ?>/manager/images/loading.gif" class="loading-img one hide">
                                    <span class="text-success message-status1"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <input type="submit" value="CONTINUE" name="submit-btn" class="btn btn-primary" id="submit-btn">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-8">
                            <p>
                                <em> Clicking on continue will take you to the City, State screen.<br>
                                    You will be able to select where you want the job posted by specific Cities & States.<br>
                                    You can also choose the # of job post per that City, State.</em>
                            </p>
                        </div>
                    </div>
                    <?php
                    \yii\widgets\ActiveForm::end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
