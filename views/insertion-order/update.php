<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
?>
<section class = "content-header">
    <h1>
        <span>Manager > </span><span class="blue-span"><a href="<?php echo Url::to(['insertion-order/index']); ?>">I/O Overview > </a></span><a href="<?php echo Url::to(['insertion-order/index']); ?>">Publisher Name I/O Overview > </a><span>Update Publisher Name I/O</span>
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
