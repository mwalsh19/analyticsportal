<?php
/* @var $this yii\web\View */

use app\assets\DataTableAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Alert;

DataTableAsset::register($this);

$this->registerJsFile(Url::base() . '/manager/js/filters.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$canCreate = Yii::$app->user->can('title/create');
$canUpdate = Yii::$app->user->can('title/update');
$canDelete = Yii::$app->user->can('title/delete');
?>
<section class="content-header">
    <h1>
        <span>Manager</span> > <span>Titles Overview</span>
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
                            <h4>Titles Overview</h4>
                        </div>
                        <?php
                        if ($canCreate) {
                            ?>
                            <div class="pull-right">
                                <a href="<?= Url::to(['title/create']); ?><?php echo!empty($queryString) ? '?' . $queryString : '' ?>" class="btn btn-primary btn-bg btn-create">
                                    <strong>Add Title</strong> <i class="glyphicon glyphicon-plus"></i>
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
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
                                <th>Title</th>
                                <th>Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($titles)) {
                                $counter = 1;
                                for ($index = 0; $index < count($titles); $index++) {
                                    $id_title = $titles[$index]->id_title;
                                    $segmentObject = $titles[$index]->tblXmlSegmentIdSegment;
                                    $publisherObject = $titles[$index]->tblXmlPublisherIdPublisher;

                                    $deleteUrl = Url::to(['title/delete', 'id' => $id_title]);
                                    if (!empty($queryString)) {
                                        $deleteUrl = $deleteUrl . '&' . $queryString;
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $counter ?></td>
                                        <td style="text-align: center;"><input type="checkbox" name="delete-check" class="delete-check" data-id="<?php echo $id_title; ?>"></td>
                                        <td style="width: 80px;"><?php echo $publisherObject->name ?></td>
                                        <td style="width: 80px;"><?php echo $segmentObject->name ?></td>
                                        <td><?php echo $titles[$index]->title ?></td>
                                        <td style="width: 150px;">
                                            <?php
                                            if ($canUpdate) {
                                                ?>
                                                <a href="<?= Url::to(['title/update', 'id' => $id_title]) ?><?php echo!empty($queryString) ? '&' . $queryString : '' ?>" class="btn btn-success btn-sm">
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
                                                        'confirm' => "Are you sure you want to delete the title?",
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