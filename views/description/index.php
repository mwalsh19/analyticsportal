<?php
/* @var $this yii\web\View */

use app\assets\DataTableAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Alert;

DataTableAsset::register($this);

$this->registerJsFile(Url::base() . '/manager/js/filters.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$canCreate = Yii::$app->user->can('description/create');
$canUpdate = Yii::$app->user->can('description/update');
$canDelete = Yii::$app->user->can('description/delete');
?>
<section class="content-header">
    <h1>
        <span>Manager</span> > <span>Descriptions Overview</span>
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
    if ($session->hasFlash('error')) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-danger',
                'style' => 'margin: 0; margin-top: 10px;'
            ],
            'body' => $session->getFlash('error'),
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
                            <h4>Descriptions Overview</h4>
                        </div>
                        <?php
                        if ($canCreate) {
                            ?>
                            <div class="pull-right">
                                <a href="<?= Url::to(['description/create']); ?><?php echo!empty($queryString) ? '?' . $queryString : '' ?>" class="btn btn-primary btn-bg btn-create">
                                    <strong>Add Description</strong> <i class="glyphicon glyphicon-plus"></i>
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="clearfix">
                        <?php
                        echo $this->render('//partials/_filters');
                        ?>
                    </div>
                    <br>
                    <table class="table table-bordered table-hover table-striped" id="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th style="text-align: center;">
                                    <a href="#" class="btn btn-primary btn-sm delete-checked-items"><i class="glyphicon glyphicon-trash"></i></a>
                                </th>
                                <th>Publisher</th>
                                <th>Segment</th>
                                <th>Description</th>
                                <th>Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($descriptions)) {
                                $counter = 1;
                                for ($index = 0; $index < count($descriptions); $index++) {
                                    $id_description = $descriptions[$index]->id_description;
                                    $publisherObject = $descriptions[$index]->tblXmlPublisherIdPublisher;
                                    $segmentObject = $descriptions[$index]->tblXmlSegmentIdSegment;

                                    $deleteUrl = Url::to(['description/delete', 'id' => $id_description]);
                                    if (!empty($queryString)) {
                                        $deleteUrl = $deleteUrl . '&' . $queryString;
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $counter ?></td>
                                        <td style="text-align: center;"><input type="checkbox" name="delete-check" class="delete-check" data-id="<?php echo $id_description; ?>"></td>
                                        <td style="width: 80px;"><?php echo $publisherObject->name ?></td>
                                        <td><?php echo $segmentObject->name ?></td>
                                        <td>
                                            <?php
                                            if ($descriptions[$index]->contain_html) {
                                                ?>
                                                <a href="<?= Url::to(['description/detail', 'id' => $id_description]) ?><?php echo!empty($queryString) ? '&' . $queryString : '' ?>" class="btn btn-primary btn-sm">More</a>
                                                <?php
                                            } else {
                                                echo $descriptions[$index]->description;
                                            }
                                            ?>
                                        </td>
                                        <td style="width: 150px;">
                                            <?php
                                            if ($canUpdate) {
                                                ?>
                                                <a href="<?= Url::to(['description/update', 'id' => $id_description]) ?><?php echo!empty($queryString) ? '&' . $queryString : '' ?>" class="btn btn-success btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i>
                                                </a>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            if ($canDelete) {
                                                echo Html::a('<i class="glyphicon glyphicon-trash"></i>', $deleteUrl, [
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data' => [
                                                        'confirm' => "Are you sure you want to delete the description?",
                                                        'method' => 'post',
                                                    ],
                                                ]);
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $counter++;
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th style="text-align: center;">
                                    <a href="#" class="btn btn-primary btn-sm delete-checked-items"><i class="glyphicon glyphicon-trash"></i></a>
                                </th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>