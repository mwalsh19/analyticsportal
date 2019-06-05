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

$canCreate = Yii::$app->user->can('publisher/create');
$canUpdate = Yii::$app->user->can('publisher/update');
$canDelete = Yii::$app->user->can('publisher/delete');
?>
<section class="content-header">
    <h1>
        <span>Manager</span> > <span>Publishers Overview</span>
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
                            <h4>Publishers Overview</h4>
                        </div>
                        <?php
                        if ($canCreate) {
                            ?>
                            <div class="pull-right">
                                <a href="<?= Url::to(['publisher/create']); ?>" class="btn btn-primary btn-bg btn-create">
                                    <strong>Add Publisher</strong> <i class="glyphicon glyphicon-plus"></i>
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
                                <th>Publisher</th>
                                <th>Company</th>
                                <th>Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($publishers)) {
                                for ($index = 0; $index < count($publishers); $index++) {
                                    $id_publisher = $publishers[$index]->id_publisher;
                                    $companyObject = $publishers[$index]->tblCompanyIdCompany;
                                    ?>
                                    <tr>
                                        <td><?php echo $publishers[$index]->name ?></td>
                                        <td><?php echo $companyObject->name ?></td>
                                        <td>
                                            <?php
                                            if ($canUpdate) {
                                                ?>
                                                <a href="<?= Url::to(['publisher/update', 'id' => $id_publisher]) ?>" class="btn btn-success btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i>
                                                </a>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            if ($canDelete) {
                                                echo Html::a('<i class="glyphicon glyphicon-trash"></i>', ['publisher/delete', 'id' => $id_publisher], [
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data' => [
                                                        'confirm' => "Are you sure you want to delete the publisher?",
                                                        'method' => 'post',
                                                    ],
                                                ]);
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td>Nothing to show</td>
                                    <td></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>