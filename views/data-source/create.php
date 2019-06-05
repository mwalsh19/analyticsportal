<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$isCallSource = app\models\DataSource::isCallSource();
$type = '';

if ($isCallSource) {
    $type = 'Call';
} else {
    $type = 'Tenstreet';
}

$fromInitial = isset($_GET['fromInitial']) && $_GET['fromInitial'];
$fromOverview = isset($_GET['fromOverview']) && $_GET['fromOverview'];

$overviewUrl = ['data-source/index'];
if ($isCallSource) {
    $overviewUrl['type'] = 'call';
} else {
    $overviewUrl['type'] = 'data';
}
if ($fromInitial) {
    $overviewUrl['fromInitial'] = 1;
}
if ($fromOverview) {
    $overviewUrl['fromOverview'] = 1;
}
?>
<section class = "content-header clearfix">
    <h1 class = "pull-left">
        <span>Manager </span> >
        <?php
        if ($fromInitial) {
            ?>
            <span class="blue-span"><a href="<?php echo Url::to(['data-source/initial']); ?>">Data Sources</a></span>
            >
            <?php
        }
        ?>
        <?php
        if ($fromOverview) {
            ?>
            <span class="blue-span"><a href="<?php echo Url::to($overviewUrl); ?>"><?php echo $type; ?> Data Sources Overview</a></span>
            >
            <?php
        }
        ?>
        <span>Add <?php echo $type; ?> Data Source</span>
    </h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-10">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php
                            echo $this->render('_form', ['model' => $model]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
