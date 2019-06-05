<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
use app\assets\DataTableAssetNoLegacy;

DataTableAssetNoLegacy::register($this);

$basePath = Url::base();
$params = [
    'position' => yii\web\View::POS_END,
    'depends' => app\assets\ManagerAsset::className()
];
$this->registerJsFile($basePath . '/manager/js/sort_and_filter.js', $params);
?>

<section class="content-header">
    <h1>
        <span>Tenstreet > </span></span> Sort & Filter
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
                            $items = [];
                            $total = count($dataSources);
                            for ($index = 0; $index < $total; $index++) {
                                $dataSourceArray = $dataSources[$index];
                                $items[$dataSourceArray['owner'] . '_' . $dataSourceArray['id_file']] = !empty($dataSourceArray['label']) ? $dataSourceArray['label'] : $dataSourceArray['file'];
                            }

                            echo yii\helpers\Html::dropDownList('data-source-select', null, $items, [
                                'prompt' => '-- Select a Data Source -- ',
                                'style' => 'max-width: 320px; display: inline-block;',
                                'class' => 'form-control'
                            ]);
                            echo "<br>";
                        } else {
                            echo "<h4>You do not have data sources available, please upload a data source to continue.</h4>";
                        }
                        ?>
                    </div>
                    <br>
                    <br>
                    <table class="table table-striped table-bordered table-condensed table-responsive" cellspacing="0" width="100%" style="font-size: 13px; width: auto;">
                        <thead>
                            <tr class="totals-row">
                                <th>Total (viewable columns)</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th>Referrer Code</th>
                                <th>Total (all Tenstreet available rows)</th>
                                <th>Hired</th>
                                <th>Attending Academy</th>
                                <th>Not Qualified</th>
                                <th>Not Interested</th>
                                <th>No Response</th>
                                <th>Duplicate App</th>
                                <th>Unqualified Da</th>
                                <th>Do Not Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="totals-row">
                                <th>Total (viewable columns)</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            <tr style="background: #000; color: #fff;" class="grand-total-row">
                                <th style="font-weight: bold;">Grand Total</th>
                                <th class="grand-total" colspan="9" style="text-align: left; font-weight: bold;">0</th>
                            </tr>
                        </tfoot>
                    </table>
                    <div  class="filename hide">SwiftPortal_Report_Sort_and_Filter_<?php echo date('M_d_Y_H_i'); ?></div>
                    <div style="min-height: 200px; position: relative;">
                        <div id="loading" style="display: none;"><div class='uil-reload-css' style='-webkit-transform:scale(0.6)'><div></div></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
