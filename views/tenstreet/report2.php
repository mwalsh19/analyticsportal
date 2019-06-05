<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$basePath = Url::base();
$params = [
    'position' => yii\web\View::POS_END,
    'depends' => app\assets\ManagerAsset::className()
];
$this->registerJsFile($basePath . '/manager/js/report2.js', $params);
?>

<section class="content-header">
    <h1>
        <span>Tenstreet > </span></span> Data Sources
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
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Data Source (Referrer)</label><br>
                                            <?php
                                            echo yii\helpers\Html::dropDownList('data-source-select-referrer', null, $referrerItems, [
                                                'prompt' => '-- Select a Data Source -- ',
                                                'style' => 'max-width: 320px; display: inline-block;',
                                                'class' => 'form-control'
                                            ]);
                                            ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Data Source (Source)</label><br>
                                            <?php
                                            echo yii\helpers\Html::dropDownList('data-source-select-source', null, $sourceItems, [
                                                'prompt' => '-- Select a Data Source -- ',
                                                'style' => 'max-width: 320px; display: inline-block;',
                                                'class' => 'form-control'
                                            ]);
                                            ?>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Data Source (Referrer)</label><br>
                                            <?php
                                            echo yii\helpers\Html::dropDownList('data-source-select-referrer-2', null, $referrerItems, [
                                                'prompt' => '-- Select a Data Source 2015 -- ',
                                                'style' => 'max-width: 320px; display: inline-block;',
                                                'class' => 'form-control'
                                            ]);
                                            ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Data Source (Source)</label><br>
                                            <?php
                                            echo yii\helpers\Html::dropDownList('data-source-select-source-2', null, $sourceItems, [
                                                'prompt' => '-- Select a Data Source 2015 -- ',
                                                'style' => 'max-width: 320px; display: inline-block;',
                                                'class' => 'form-control'
                                            ]);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label>Media Publishers</label><br>
                                            <?php
                                            echo yii\helpers\Html::dropDownList('publishers-select', null, $publishers, [
                                                'prompt' => '-- Select a Media Publisher -- ',
                                                'style' => 'max-width: 320px; display: inline-block;',
                                                'class' => 'form-control'
                                            ]);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div style="padding-top: 45px">
                                                <a href="#" class="btn btn-primary show-result">Show</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            echo "<br>";
                        } else {
                            echo "<h4>You do not have data sources available, please upload a data source to continue.</h4>";
                        }
                        ?>
                        <span class="customDateVal">&nbsp;<strong></strong></span>
                    </div>
                    <div style="position: relative; min-height: 280px;">
                        <div id="tables-remote"></div>
                        <div id="loading" style="display: none;"><div class='uil-reload-css' style='-webkit-transform:scale(0.6)'><div></div></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
