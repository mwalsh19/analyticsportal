
<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\bootstrap\Alert;
use app\assets\DataTableAssetNoLegacy;

DataTableAssetNoLegacy::register($this);


$canCreate = Yii::$app->user->can('data-source/create');
$canDelete = Yii::$app->user->can('data-source/delete');

$isCallSource = app\models\DataSource::isCallSource();
$type = '';

if ($isCallSource) {
    $this->registerJsFile(Url::base() . '/manager/js/call-source.js', ['position' => yii\web\View::POS_END, 'depends' => DataTableAssetNoLegacy::className()]);
    $type = 'Call';
} else {
    $this->registerJsFile(Url::base() . '/manager/js/data-source.js', ['position' => yii\web\View::POS_END, 'depends' => DataTableAssetNoLegacy::className()]);
    $type = 'Tenstreet';
}

$fromInitial = isset($_GET['fromInitial']) && $_GET['fromInitial'];
$createUrl = ['data-source/create'];
$deleteUrl = ['data-source/delete'];

$createUrl['type'] = $isCallSource ? 'call' : 'data';
$deleteUrl['type'] = $isCallSource ? 'call' : 'data';

if ($fromInitial) {
    $createUrl['fromInitial'] = 1;
    $deleteUrl['fromInitial'] = 1;
}
$createUrl['fromOverview'] = 1;
$deleteUrl['fromOverview'] = 1;
?>
<section class="content-header">
    <h1>

        <span>Manager</span> >
        <?php
        if (isset($_GET['fromInitial']) && $_GET['fromInitial'] == 1) {
            ?>
            <span class="blue-span"><a href="<?php echo Url::to(['data-source/initial']); ?>">Data Sources</a></span>
            >
            <?php
        }
        ?>
        <span><?php echo $type; ?> Data Sources Overview</span>
    </h1>
    <?php
    $session = Yii::$app->session;
    if ($session->hasFlash('error')) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-danger',
            ],
            'body' => $session->get('error'),
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
                        <div class="pull-left">
                            <h4><?php echo $type; ?> Data Sources Overview</h4>
                        </div>
                        <?php
                        if ($canCreate) {
                            ?>
                            <div class="pull-right">
                                <a href="<?= Url::to($createUrl); ?>" class="btn btn-primary btn-bg btn-create">
                                    <strong>Add <?php echo $type; ?> Source</strong> <i class="glyphicon glyphicon-plus"></i>
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <table class="display nowrap" cellspacing="0" width="100%" style="font-size: 13px;">
                        <?php
                        if (!$isCallSource) {
                            ?>
                            <thead>
                                <tr>
                                    <th>Label</th>
                                    <th>Total leads</th>
                                    <th>Conversions</th>
                                    <th>Hires</th>
                                    <th>Attending Academy</th>
                                    <th>Owner</th>
                                    <th>Month</th>
                                    <th>Year</th>
                                    <th>Create Date</th>
                                    <th>Tools</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <?php
                        } else {
                            ?>
                            <thead>
                                <tr>
                                    <th>Label</th>
                                    <th>Month</th>
                                    <th>Year</th>
                                    <th>Create Date</th>
                                    <th>Tools</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($sources)) {
                                    $monthsArray = \app\components\Utils::getMonthArray();
                                    foreach ($sources as $source) {
                                        ?>
                                        <tr>
                                            <td><?php echo $source->label; ?></td>
                                            <td><?php echo $monthsArray[$source->month] ?></td>
                                            <td><?php echo $source->year; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($source->create_date)); ?></td>
                                            <td>
                                                <?php
                                                if ($canDelete) {
                                                    $deleteUrl['id'] = $source->id_call_source;

                                                    echo yii\helpers\Html::a('<i class="glyphicon glyphicon-trash"></i>', $deleteUrl, [
                                                        'class' => 'btn btn-danger btn-sm',
                                                        'data' => [
                                                            'confirm' => "Are you sure you want to delete the file source?",
                                                            'method' => 'post',
                                                        ]
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
                            <?php
                        }
                        ?>
                    </table>

                    <div  class="filename hide">SwiftPortal_Report_<?php echo $type; ?>_Sources_<?php echo date('M_d_Y_H_i'); ?></div>
                    <div class="table-loading" style="min-height: 200px; position: relative;">
                        <div id="loading"><div class='uil-reload-css' style='-webkit-transform:scale(0.6)'><div></div></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
if (!$isCallSource) {
    ?>
    <div class="custom-overlay-2" id="custom-overlay" style="display: none;">
        <div class="custom-overlay-2-content">
            <div class="overlay-wrap">
                <div class="overlay-text">
                    <h2>Share file with users</h2>
                </div>
                <div class="users-container">
                    <div class="user-inputs"></div>
                    <div id="loading"><div class='uil-reload-css' style='-webkit-transform:scale(0.6)'><div></div></div></div>
                </div>
                <div class="actions-btn">
                    <a href="javascript:void(0);" class="btn btn-info" id="share-now-btn">Share</a>
                    <a href="javascript:void(0);" class="btn btn-danger" id="cancel-btn">Cancel</a>
                </div>
            </div>
        </div>
    </div>

    <?php
}
?>
