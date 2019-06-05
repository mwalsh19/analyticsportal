<?php
/* @var $this yii\web\View */

use app\assets\DataTableAssetNoLegacy;

DataTableAssetNoLegacy::register($this);
$this->registerJsFile(yii\helpers\Url::base() . '/manager/js/tenstreet-import.js', ['depends' => DataTableAssetNoLegacy::className()]);
$canImportFile = Yii::$app->user->can('tenstreet/import-file');
//
$year = !empty($_GET['year']) ? $_GET['year'] : null;
$month = !empty($_GET['month']) ? $_GET['month'] : null;
?>

<section class="content-header">
    <h1>
        <span>Tenstreet > </span></span> Tenstreet Import
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div style="width: 98%; margin: 0 auto;">
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Year</label><br>
                                <?php
                                $years = [];
                                $current_month = date('Y');
                                $up5years = (int) $current_month + 5;

                                for ($index1 = 0; $index1 < 10; $index1++) {
                                    $years[$up5years - $index1] = $up5years - $index1;
                                }
                                echo yii\helpers\Html::dropDownList('years-select', $year, $years, [
                                    'class' => 'form-control max-width-300',
                                    'prompt' => '-- Select a Year --'
                                ]);
                                ?>

                            </div>
                            <div class="col-sm-3">
                                <label>Month</label><br>
                                <?php
                                echo yii\helpers\Html::dropDownList('months-select', $month, \app\components\Utils::getMonthArray(), [
                                    'class' => 'form-control max-width-300',
                                    'prompt' => '-- Select a Month --'
                                ]);
                                ?>
                            </div>
                            <div class="col-sm-3">
                                <div style="padding-top: 23px;"><a href="#" class="btn btn-primary show-result">Show</a></div>
                            </div>
                        </div>
                        <table id="table" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>File</th>
                                    <th>Company</th>
                                    <th>Create Date</th>
                                    <th>Tools</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>File</th>
                                    <th>Company</th>
                                    <th>Create Date</th>
                                    <th>Tools</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                date_default_timezone_set('America/Los_Angeles');

                                if (!empty($tenstreet_files)) {
                                    $baseUrl = YII_ENV == 'dev' ? 'http://tenstreet.lacedagency.dev:8080/uploads/' : 'http://tenstreet.lacedagency.com/uploads/';
                                    $total = count($tenstreet_files);
                                    for ($index = 0; $index < $total; $index++) {
                                        $object = $tenstreet_files[$index];
                                        ?>
                                        <tr>
                                            <td><?php echo $object->file ?></td>
                                            <td>
                                                <?php
                                                echo!empty($object->id_company_user) ? $object->companyInfo->name : 'N/A';
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo date('Y, M d g:i A', $object->create_date) ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($canImportFile) {
                                                    ?>
                                                    <a title="Import File" href="#" class="btn btn-primary btn-sm import-file" data-file="<?php echo $baseUrl . $object->file; ?>" data-file-name="<?php echo $object->file; ?>" data-id-file="<?php echo $object->id_file; ?>"
                                                       ><i class="glyphicon glyphicon-upload"></i>
                                                    </a>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <div style="min-height: 200px;">
                            <div id="loading"><div class='uil-reload-css' style='-webkit-transform:scale(0.6)'><div></div></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
