<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->registerJs("
    $('.check-all').on('click', function(){
        if($(this).is(':checked')){
            $('#roleform-permissions input').prop('checked', true);
        }else{
            $('#roleform-permissions input').prop('checked', false);
        }
    });
");
?>
<section class = "content-header clearfix">
    <h1 class = "pull-left">
        <span>Manager > </span><span class="blue-span"><a href="<?php echo Url::to(['roles/index']); ?>">Roles Overview</a> > </span><span class="blue-span"><?php echo!empty($_GET['role']) ? $_GET['role'] : 'Role name' ?></span> > <span>Assign Permissions</span>
    </h1>
</section>
<section class="content" id="<?php echo Yii::$app->controller->id ?>">
    <div class="row">
        <div class="col-xs-10">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php
                            $form = ActiveForm::begin([
                                        'id' => 'form'
                            ]);
                            ?>
                            <?php
                            if (!empty($permissions)) {
                                ?>
                                <div class="well well-lg">
                                    <label>Check All Permissions <input type="checkbox" class="check-all"></label>
                                    <div class="form-group">
                                        <?php echo $form->field($model, 'permissions', ['template' => '{label}{input}'])->checkBoxList($permissions) ?>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <div class="pull-right">
                                        <a href="<?= Url::to(['roles/index']); ?>" class="btn btn-default btn-lg">CANCEL</a>
                                        <input type="submit" class="btn btn-primary btn-lg" value="SAVE">
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                Permissions not found, please create a permission to assign<br>
                                <a href="<?= Url::to(['permission/create']); ?>">Create Permission</a>
                                <?php
                            }
                            ?>


                            <?php
                            ActiveForm::end();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
