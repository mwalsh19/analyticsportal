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

$canCreate = Yii::$app->user->can('campaign/create');
$canUpdate = Yii::$app->user->can('campaign/update');
$canDelete = Yii::$app->user->can('campaign/delete');
?>
<section class="content-header">
    <h1>
        <span>Manager</span> > <span>Campaigns Overview</span>
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
                            <h4>Campaigns Overview</h4>
                        </div>
                        <?php
                        if ($canCreate) {
                            ?>
                            <div class="pull-right">
                                <a href="<?= Url::to(['campaign/create']); ?>" class="btn btn-primary btn-bg btn-create">
                                    <strong>Add Campaign</strong> <i class="glyphicon glyphicon-plus"></i>
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
                                <th>Campaign Name</th>
                                <th>Status</th>
                                <th>Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($campaigns)) {

                                for ($index = 0; $index < count($campaigns); $index++) {
                                    $id_campaign = $campaigns[$index]->id_campaign;
                                    ?>
                                    <tr>
                                        <td><?php echo $campaigns[$index]->name ?></td>
                                        <td>
                                            <?php
                                            echo ($campaigns[$index]->status) ? '<span class="label label-success">Enabled</span>' : '<span class="label label-danger">Disabled</span>';
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($canUpdate) {
                                                ?>
                                                <a href="<?= Url::to(['campaign/update', 'id' => $id_campaign]) ?>" class="btn btn-success btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i>
                                                </a>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            if ($canDelete) {
                                                echo Html::a('<i class="glyphicon glyphicon-trash"></i>', ['campaign/delete', 'id' => $id_campaign], [
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data' => [
                                                        'confirm' => "Are you sure you want to delete the campaign?",
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