<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$this->registerJsFile(Url::base() . '/manager/js/executive_report.js', [
    'position' => yii\web\View::POS_END,
    'depends' => app\assets\ExcelBuilderAsset::className()
]);
?>

<section class="content-header">
    <h1>
        <span>Tenstreet > </span></span> YOY Executive Report
    </h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="clearfix">
                        <?php
                        if (!empty($dataSources)) {
                            $referrerItems = [];
                            $sourceItems = [];
                            $total = count($dataSources);
                            for ($index = 0; $index < $total; $index++) {
                                $dataSourceArray = $dataSources[$index];
                                $label = !empty($dataSourceArray['label']) ? $dataSourceArray['label'] : $dataSourceArray['file'];
                                $item = $dataSourceArray['owner'] . '_' . $dataSourceArray['id_file'];

                                if ($dataSourceArray['code_type'] == "referrer") {
                                    $referrerItems[$item] = $label;
                                    continue;
                                }
                                if ($dataSourceArray['code_type'] == "source") {
                                    $sourceItems[$item] = $label;
                                }
                            }
                            ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <fieldset>
                                        <legend>Month to Date:</legend>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label>Data Source (Referrer) <span class="text-red">*</span></label><br>
                                                <?php
                                                echo yii\helpers\Html::dropDownList('md-referrer-select', null, $referrerItems, [
                                                    'prompt' => '-- Select a Data Source -- ',
                                                    'style' => 'max-width: 320px; display: inline-block;',
                                                    'class' => 'form-control'
                                                ]);
                                                ?>
                                            </div>
                                            <div class="col-sm-4">
                                                <label>Data Source (Source) <span class="text-red">*</span></label><br>
                                                <?php
                                                echo yii\helpers\Html::dropDownList('md-source-select', null, $sourceItems, [
                                                    'prompt' => '-- Select a Data Source -- ',
                                                    'style' => 'max-width: 320px; display: inline-block;',
                                                    'class' => 'form-control'
                                                ]);
                                                ?>
                                            </div>
                                            <div class="col-sm-4">
                                                <label>Call Sources <span class="text-red">*</span></label><br>
                                                <?php
                                                echo yii\helpers\Html::dropDownList('md-call-source-select', null, \app\models\CallSource::getCallSourcesItems(), [
                                                    'prompt' => '-- Select a Call Source -- ',
                                                    'style' => 'max-width: 320px; display: inline-block;',
                                                    'class' => 'form-control'
                                                ]);
                                                ?>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <br>
                                    <fieldset>
                                        <legend>Previous Date:</legend>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label>Data Source (Referrer) <span class="text-red">*</span></label><br>
                                                <?php
                                                echo yii\helpers\Html::dropDownList('prev-referrer-select', null, $referrerItems, [
                                                    'prompt' => '-- Select a Data Source -- ',
                                                    'style' => 'max-width: 320px; display: inline-block;',
                                                    'class' => 'form-control'
                                                ]);
                                                ?>
                                            </div>
                                            <div class="col-sm-4">
                                                <label>Data Source (Source) <span class="text-red">*</span></label><br>
                                                <?php
                                                echo yii\helpers\Html::dropDownList('prev-source-select', null, $sourceItems, [
                                                    'prompt' => '-- Select a Data Source -- ',
                                                    'style' => 'max-width: 320px; display: inline-block;',
                                                    'class' => 'form-control'
                                                ]);
                                                ?>
                                            </div>

                                            <div class="col-sm-4">
                                                <label>Call Sources <span class="text-red">*</span></label><br>
                                                <?php
                                                echo yii\helpers\Html::dropDownList('prev-call-source-select', null, \app\models\CallSource::getCallSourcesItems(), [
                                                    'prompt' => '-- Select a Call Source -- ',
                                                    'style' => 'max-width: 320px; display: inline-block;',
                                                    'class' => 'form-control'
                                                ]);
                                                ?>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-left">
                                        <div style="margin-top: 20px;">
                                            <a href="#" class="btn btn-primary btn-lg show-result">Show</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                        } else {
                            echo "<h4>You do not have data sources available, please upload a data source to continue.</h4>";
                        }
                        ?>
                    </div>
                    <div style="position: relative; min-height: 280px;">
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="clearfix">
                                    <div class="pull-right">
                                        <a href="#" class="btn btn-primary hide" id="downloader"  download="SwiftPortal_Report_Media_Executive_Report_<?php echo date('M_d_Y_H_i') ?>.xlsx">Export Report</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tables-remote"></div>
                        <div id="loading" style="display: none;"><div class='uil-reload-css' style='-webkit-transform:scale(0.6)'><div></div></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
