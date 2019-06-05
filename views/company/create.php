<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
?>
<section class = "content-header clearfix">
    <h1 class = "pull-left">
        <span>Manager > </span><span class="blue-span"><a href="<?php echo Url::to(['company/index']); ?>">Companies Overview</a> > </span><span>Add Company</span>
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