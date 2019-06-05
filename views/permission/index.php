<?php
/* @var $this yii\web\View */
?>
<?php
/* @var $this yii\web\View */

use app\assets\DataTableAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Alert;

DataTableAsset::register($this);

$this->registerJs("
     $('#table').dataTable({
         iDisplayLength: 100
    });
");

$canCreate = Yii::$app->user->can('permission/create');
$canUpdate = Yii::$app->user->can('permission/update');
$canDelete = Yii::$app->user->can('permission/delete');
?>

<section class="content-header">
    <h1>
        <span>Manager</span> > <span>Permissions Overview</span>
    </h1>
    <?php
    $session = Yii::$app->session;
    if ($session->hasFlash('success')) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success',
                'style' => 'margin: 0; margin-top: 10px;'
            ],
            'body' => $session->get('success'),
        ]);
    }
    ?>
    <?php
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
<section class="content" id="<?php echo Yii::$app->controller->id ?>">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4>Permissions Overview</h4>
                        </div>
                        <?php
                        if ($canCreate) {
                            ?>
                            <div class="pull-right">
                                <a href="<?= Url::to(['permission/create']); ?>" class="btn btn-primary btn-bg btn-create">
                                    <strong>Add Permission</strong> <i class="glyphicon glyphicon-plus"></i>
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
                                <th>Permission Name</th>
                                <th>Permission Description</th>
                                <th>Created Date</th>
                                <th>Last Update</th>
                                <th>Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($permissions)) {
                                $total = count($permissions);
                                foreach ($permissions as $key => $permissionObject) {
                                    ?>
                                    <tr>
                                        <td><?php echo $permissionObject->name ?></td>
                                        <td><?php echo!empty($permissionObject->description) ? $permissionObject->description : 'N/A'; ?></td>
                                        <td><?php echo date('M d, Y g:i A', $permissionObject->createdAt) ?></td>
                                        <td><?php echo date('M d, Y g:i A', $permissionObject->updatedAt) ?></td>
                                        <td>
                                            <?php
                                            if ($canUpdate) {
                                                ?>
                                                <a href="<?php echo Url::to(['permission/update', 'permission' => $permissionObject->name]); ?>" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                                    <?php
                                                }
                                                ?>
                                                <?php
                                                if ($canDelete) {
                                                    echo Html::a('<i class="glyphicon glyphicon-trash"></i>', ['permission/delete', 'permission' => $permissionObject->name], [
                                                        'class' => 'btn btn-danger btn-sm',
                                                        'data' => [
                                                            'confirm' => "Are you sure you want to delete the permission?",
                                                            'method' => 'post',
                                                        ],
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
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
