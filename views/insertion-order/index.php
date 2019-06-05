<?php
/* @var $this yii\web\View */

use app\assets\DataTableAsset;
use yii\bootstrap\Alert;

DataTableAsset::register($this);
$this->registerJs("
     $('#table').dataTable();
");
?>
<section class="content-header">
    <h1>
        <span>Manager</span> > <span>I/O Overview</span>
    </h1>
    <?php
    $session = Yii::$app->session;
    if ($session->hasFlash('error')) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-danger',
            ],
            'body' => $session->getFlash('error'),
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
    ?>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="clearfix">
                        <h4>I/O Overview</h4>
                    </div>
                    <div class="clearfix">
                        <select class="form-control max-width-200" style="display: inline-block; margin-right: 10px;">
                            <option value="">Month</option>
                            <?php
                            $months = \app\components\Utils::getMonthArray();
                            foreach ($months as $key => $value) {
                                echo "<option value='$key'>$value</option>";
                            }
                            ?>
                        </select>
                        <select class="form-control max-width-200" style="display: inline-block;">
                            <option value="">Year</option>
                            <?php
                            $currentYear = date('Y');
                            $currentYearPlus = $currentYear + 5;
                            $currentYearMinus = $currentYear - 70;
                            for ($index = $currentYearPlus; $index > $currentYearMinus; $index--) {
                                echo "<option value='$index'>$index</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="clearfix">
                        <div class="pull-right">
                            <a href="<?= \yii\helpers\Url::to(['insertion-order/create']); ?>" class="btn btn-primary btn-bg btn-create">
                                <strong>Add I/O</strong> <i class="glyphicon glyphicon-plus"></i>
                            </a>
                        </div>
                    </div>
                    <br>
                    <table class="table table-bordered table-hover table-striped" id="table">
                        <thead>
                            <tr>
                                <th class="text-bold text-custom-size text-custom-color">Publisher</th>
                                <th>Month</th>
                                <th>Year</th>
                                <th>I/O Number</th>
                                <th>Net</th>
                                <th>Gross</th>
                                <th>Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($index1 = 0; $index1 < 50; $index1++) {
                                ?>
                                <tr>
                                    <td class="text-bold text-custom-size text-custom-color">
                                        <a href="<?= \yii\helpers\Url::to(['insertion-order-by-publisher']); ?>">Publisher 1</a>
                                    </td>
                                    <td>October</td>
                                    <td>2016</td>
                                    <td class="text-custom-color">2016DR_MediaBuy</td>
                                    <td><strong>$2,500</strong>.00</td>
                                    <td><strong>$3,100</strong>.00</td>
                                    <td>
                                        <?php
                                        echo \yii\helpers\Html::a('<i class="glyphicon glyphicon-trash"></i>', ['insertion-order/delete'], [
                                            'class' => 'btn btn-danger btn-sm',
                                            'data' => [
                                                'confirm' => "Are you sure to delete this order?",
                                                'method' => 'post',
                                            ],
                                        ]);
                                        ?>
                                        <a href="<?= yii\helpers\Url::to(['insertion-order/update']) ?>" class="btn btn-success btn-sm" style="margin-left: 10px;">
                                            <i class="glyphicon glyphicon-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-success btn-sm" style="margin-left: 10px; background-color: #00b0b4;">
                                            <i class="glyphicon glyphicon-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-info btn-sm" style="margin-left: 10px;">
                                            <i class="glyphicon glyphicon-download"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                                <td style="background-color: #798289; color: white;"><strong>$84,000.00</strong></td>
                                <td style="background-color: #798289; color: white;"><strong>$104,000.00</strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
