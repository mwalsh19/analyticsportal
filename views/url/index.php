<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\DataTableAsset;
use yii\bootstrap\Alert;

DataTableAsset::register($this);

$this->registerJsFile(Url::base() . '/manager/js/filters.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$canCreate = Yii::$app->user->can('url/create');
$canUpdate = Yii::$app->user->can('url/update');
$canDelete = Yii::$app->user->can('url/delete');
?>
<section class="content-header">
    <h1>
        <span>Manager</span> > Urls Overview
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

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4>Urls Overview</h4>
                        </div>
                        <?php
                        if ($canCreate) {
                            ?>
                            <div class="pull-right">
                                <a href="<?= Url::to(['url/create']); ?><?php echo!empty($queryString) ? '?' . $queryString : '' ?>" class="btn btn-primary btn-bg btn-create">
                                    <strong>Add Url</strong> <i class="glyphicon glyphicon-plus"></i>
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
                    <table id="table" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th style="text-align: center;">
                                    <a href="#" class="btn btn-primary btn-sm delete-checked-items"><i class="glyphicon glyphicon-trash"></i></a>
                                </th>
                                <th>Publisher</th>
                                <th>Segment</th>
                                <th>Url</th>
                                <th>Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($urls)) {
                                $counter = 1;
                                for ($index = 0; $index < count($urls); $index++) {
                                    $id_url = $urls[$index]->id_url;
                                    $publisherObject = $urls[$index]->tblXmlPublisherIdPublisher;
                                    $segmentObject = $urls[$index]->tblXmlSegmentIdSegment;

                                    $deleteUrl = Url::to(['url/delete', 'id' => $id_url]);
                                    if (!empty($queryString)) {
                                        $deleteUrl = $deleteUrl . '&' . $queryString;
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $counter ?></td>
                                        <td style="text-align: center;"><input type="checkbox" name="delete-check" class="delete-check" data-id="<?php echo $id_url; ?>"></td>
                                        <td><?php echo $publisherObject->name; ?></td>
                                        <td><?php echo $segmentObject->name ?></td>
                                        <td><?php echo $urls[$index]->url; ?></td>
                                        <td style="width: 20%;">
                                            <?php
                                            if ($canUpdate) {
                                                ?>
                                                <a href="<?= Url::to(['url/update', 'id' => $id_url]) ?><?php echo!empty($queryString) ? '&' . $queryString : '' ?>" class="btn btn-success btn-sm">
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
                                                        'confirm' => "Are you sure you want to delete this url?",
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