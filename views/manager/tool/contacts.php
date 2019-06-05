<?php
/* @var $this yii\web\View */

use app\assets\DataTableAsset;
use yii\helpers\Url;
use yii\bootstrap\Alert;

DataTableAsset::register($this);

$this->registerJs("
     $('#table').dataTable();
");
?>
<section class="content-header">
    <h1>
        <span>Manager</span> > <span>Contact Overview</span>
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
    ?>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4>Media Publisher Overview</h4>
                        </div>
                        <div class="pull-right">
                            <a href="" class="btn btn-primary btn-bg btn-create">
                                <strong>Add Contact</strong> <i class="glyphicon glyphicon-plus"></i>
                            </a>
                        </div>
                    </div>
                    <br>
                    <table class="table table-bordered table-hover table-striped" id="table">
                        <thead>
                            <tr>
                                <th>Publisher</th>
                                <th class="text-bold text-custom-size">Name</th>
                                <th>Title</th>
                                <th class="text-custom-color">Phone</th>
                                <th class="text-custom-color">Mobile</th>
                                <th class="text-custom-color">Email</th>
                                <th>Time Zone</th>
                                <th>Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Publisher 1</td>
                                <td class="text-bold text-custom-size text-custom-color"><a href="">Mike Walsh</a></td>
                                <td>Account Manager</td>
                                <td class="text-custom-color">(555) 555-5555 ext. 123</td>
                                <td class="text-custom-color">(555) 444-4444</td>
                                <td class="text-custom-color">mwalsh@lacedagency.com</td>
                                <td>CST</td>
                                <td>
                                    <a href="" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i></a>
                                    <a href="" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>Publisher 1</td>
                                <td class="text-bold text-custom-size text-custom-color"><a href="">Mike Walsh</a></td>
                                <td>Account Manager</td>
                                <td class="text-custom-color">(555) 555-5555 ext. 123</td>
                                <td class="text-custom-color">(555) 444-4444</td>
                                <td class="text-custom-color">mwalsh@lacedagency.com</td>
                                <td>CST</td>
                                <td>
                                    <a href="" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i></a>
                                    <a href="" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>Publisher 1</td>
                                <td class="text-bold text-custom-size text-custom-color"><a href="">Mike Walsh</a></td>
                                <td>Account Manager</td>
                                <td class="text-custom-color">(555) 555-5555 ext. 123</td>
                                <td class="text-custom-color">(555) 444-4444</td>
                                <td class="text-custom-color">mwalsh@lacedagency.com</td>
                                <td>CST</td>
                                <td>
                                    <a href="" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i></a>
                                    <a href="" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>Publisher 1</td>
                                <td class="text-bold text-custom-size text-custom-color"><a href="">Mike Walsh</a></td>
                                <td>Account Manager</td>
                                <td class="text-custom-color">(555) 555-5555 ext. 123</td>
                                <td class="text-custom-color">(555) 444-4444</td>
                                <td class="text-custom-color">mwalsh@lacedagency.com</td>
                                <td>CST</td>
                                <td>
                                    <a href="" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i></a>
                                    <a href="" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>Publisher 1</td>
                                <td class="text-bold text-custom-size text-custom-color"><a href="">Mike Walsh</a></td>
                                <td>Account Manager</td>
                                <td class="text-custom-color">(555) 555-5555 ext. 123</td>
                                <td class="text-custom-color">(555) 444-4444</td>
                                <td class="text-custom-color">mwalsh@lacedagency.com</td>
                                <td>CST</td>
                                <td>
                                    <a href="" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i></a>
                                    <a href="" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>