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
     $('#table').dataTable();
");

$canCreate = Yii::$app->user->can('roles/create');
$canAssign = Yii::$app->user->can('roles/assign-permissions');
$canDelete = Yii::$app->user->can('roles/delete');
?>

<section class="content-header">
    <h1>
        <span>Manager</span> > <span>Roles Overview</span>
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
                            <h4>Roles Overview</h4>
                        </div>
                        <?php
                        if ($canCreate) {
                            ?>
                            <div class="pull-right">
                                <a href="<?= Url::to(['roles/create']); ?>" class="btn btn-primary btn-bg btn-create">
                                    <strong>Add Role</strong> <i class="glyphicon glyphicon-plus"></i>
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
                                <th>Role Name</th>
                                <th>Role Description</th>
                                <th>Created Date</th>
                                <th>Last Update</th>
                                <th>Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($roles)) {
                                $total = count($roles);
                                foreach ($roles as $key => $roleObject) {
                                    ?>
                                    <tr>
                                        <td><?php echo $roleObject->name ?></td>
                                        <td><?php echo!empty($roleObject->description) ? $roleObject->description : 'N/A'; ?></td>
                                        <td><?php echo date('M d, Y g:i A', $roleObject->createdAt) ?></td>
                                        <td><?php echo date('M d, Y g:i A', $roleObject->updatedAt) ?></td>
                                        <td>
                                            <?php
                                            if ($canAssign) {
                                                ?>
                                                <a href="<?= Url::to(['roles/assign-permissions', 'role' => $roleObject->name]) ?>" class="btn btn-success btn-sm">
                                                    <i class="glyphicon glyphicon-plus"></i>
                                                    Assign Permissions
                                                </a>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            if ($canDelete) {
                                                echo Html::a('<i class="glyphicon glyphicon-trash"></i>', ['roles/delete', 'role' => $roleObject->name], [
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data' => [
                                                        'confirm' => "Are you sure you want to delete the role?",
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