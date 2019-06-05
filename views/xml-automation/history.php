<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Alert;

$canDownload = Yii::$app->user->can('xml-automation/download-file');
$canDelete = Yii::$app->user->can('xml-automation/delete-file');
?>
<section class="content-header">
    <h1>
        <span>Manager</span> > XML History
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
    if ($session->hasFlash('fail')) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-danger',
                'style' => 'margin: 0; margin-top: 10px;'
            ],
            'body' => $session->getFlash('fail'),
        ]);
    }
    ?>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body table-responsive">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4>XML History</h4>
                        </div>
                    </div>
                    <br>
                    <table id="table" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>XML</th>
                                <th>Create Date</th>
                                <th>Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $dir = Yii::getAlias('@runtime/xml/history');


                            $files = array();
                            $dir = opendir($dir);
                            while (false != ($file = readdir($dir))) {
                                if (($file != ".") and ( $file != "..") and ( $file != "index.php")) {
                                    $files[] = $file; // put in array.
                                }
                            }

                            arsort($files); // sort.

                            foreach ($files as $entry) {
                                $entry_array = explode('_', $entry);
                                $create_date = date('D d-M-Y h:i:s', str_replace('.xml', '', $entry_array[1]));
                                ?>
                                <tr>
                                    <td><a ><?php echo $entry_array[0]; ?></a></td>
                                    <td><?php echo $create_date; ?></td>
                                    <td>
                                        <?php
                                        if ($canDownload) {
                                            ?>
                                            <a href="<?php echo Url::to(['xml-automation/download-file', 'file' => $entry]) ?>" download target="_blank" class="btn btn-success btn-sm">
                                                <i class="glyphicon glyphicon-download"></i>
                                            </a>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if ($canDelete) {
                                            echo Html::a('<i class="glyphicon glyphicon-trash"></i>', Url::to(['xml-automation/delete-file', 'file' => $entry]), [
                                                'class' => 'btn btn-danger btn-sm',
                                                'data-confirm' => 'Are you sure you want to delete this item?',
                                                'data-method' => 'POST'
                                            ]);
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section><!-- /.content -->
