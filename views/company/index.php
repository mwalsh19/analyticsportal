<?php
/* @var $this yii\web\View */

use app\assets\DataTableAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Alert;

DataTableAsset::register($this);

$this->registerJs("
     $('#table').dataTable();
");
$canCreate = Yii::$app->user->can('company/create');
$canUpdate = Yii::$app->user->can('company/update');
$canDelete = Yii::$app->user->can('company/delete');
?>

<section class="content-header">
    <h1>
        <span>Manager</span> > <span>Companies Overview</span>
    </h1>
    <?php
    $session = Yii::$app->session;
    if ($session->hasFlash('error')) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-danger',
                'style' => 'margin: 0; margin-top: 10px;'
            ],
            'body' => $session->get('error'),
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
                        <div class="pull-left">
                            <h4>Companies Overview</h4>
                        </div>
                        <?php
                        if ($canCreate) {
                            ?>
                            <div class="pull-right">
                                <a href="<?= Url::to(['company/create']); ?>" class="btn btn-primary btn-bg btn-create">
                                    <strong>Add Company</strong> <i class="glyphicon glyphicon-plus"></i>
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <br>
                    <table class="table table-bordered table-hover table-striped" id="table">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($companies)) {
                                for ($index = 0; $index < count($companies); $index++) {
                                    $id_company = $companies[$index]->id_company;
                                    ?>
                                    <tr>
                                        <td style="width: 80px;"><?php echo $companies[$index]->name ?></td>
                                        <td style="width: 150px;">
                                            <?php
                                            if ($canUpdate) {
                                                ?>
                                                <a href="<?= Url::to(['company/update', 'id' => $id_company]) ?>" class="btn btn-success btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i>
                                                </a>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            if ($canDelete) {
                                                echo Html::a('<i class="glyphicon glyphicon-trash"></i>', ['company/delete', 'id' => $id_company], [
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data' => [
                                                        'confirm' => "Are you sure you want to delete the company?",
                                                        'method' => 'post',
                                                    ],
                                                ]);
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
                </div>
            </div>
        </div>
    </div>
</section>