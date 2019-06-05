
<?php
/* @var $this \yii\web\View */

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->registerJs('
    $(".toogle-companies").on("click", function(){
        if($(this).is(":checked")){
            $("#user-companies input").prop("checked", true);
        }else{
             $("#user-companies input").prop("checked", false);
        }
    });
');
?>

<?php
$form = ActiveForm::begin(['id' => 'review-form']);
?>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Basic Information</h4>
            </div>
            <div class="panel-body">
                <div class="title-container">
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'name')->textInput(); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'email')->textInput(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Roles</h4>
            </div>
            <div class="panel-body">
                <div class="title-container">
                    <div class="row">
                        <div class="col-sm-6">
                            <?=
                            $form->field($model, 'roles')->radioList($roles);
                            ?>
                        </div></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Select a role template</h4>
            </div>
            <div class="panel-body">
                <div class="title-container">
                    <div class="row">
                        <div class="col-sm-6">
                            <select class="form-control">
                                <option>Administrator</option>
                                <option>Stats Analyzer</option>
                                <option>Budget and expense</option>
                            </select>
                        </div></div>
                </div>
            </div>
        </div>
    </div>
</div>-->
<!--<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Access</h4>
                <input type="checkbox"> Toogle All
            </div>
            <div class="panel-body">
                <div class="title-container">
                    <div class="row">
                        <div class="col-sm-6">
                            <input type="checkbox"> Access 1 <br>
                            <input type="checkbox"> Access 2 <br>
                            <input type="checkbox"> Access 3 <br>
                            <input type="checkbox"> Access 4 <br>
                        </div></div>
                </div>
            </div>
        </div>
    </div>
</div>-->
<!--<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Features</h4>
                <input type="checkbox"> Toogle All
            </div>
            <div class="panel-body">
                <div class="title-container">
                    <div class="row">
                        <div class="col-sm-6">
                            <input type="checkbox"> Feature 1 <br>
                            <input type="checkbox"> Feature 2 <br>
                            <input type="checkbox"> Feature 3 <br>
                            <input type="checkbox"> Feature 4 <br>
                        </div></div>
                </div>
            </div>
        </div>
    </div>
</div>-->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Company</h4>
                <input type="checkbox" class="toogle-companies"> Toogle All
            </div>
            <div class="panel-body">
                <div class="title-container">
                    <div class="row">
                        <div class="col-sm-6">
                            <?=
                            $form->field($model, 'companies')->checkboxList(ArrayHelper::map(\app\models\CompanyUser::find()->orderBy(['name' => SORT_ASC])->all(), 'id_company_user', 'name'));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="pull-right">
            <a href="<?= Url::to(['user/index']); ?>" class="btn btn-default btn-lg">CANCEL</a>
            <input type="submit" class="btn btn-primary btn-lg" value="<?php echo $model->isNewRecord ? 'Create' : 'Update'; ?>">
        </div>
    </div>
</div>

<?php
\yii\widgets\ActiveForm::end();
?>