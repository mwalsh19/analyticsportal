<?php

use yii\helpers\Url;
use yii\helpers\Html;
?>
<section class="content-header">
    <h1>
        Manager > XML History
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">XML generated</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="table" class="table table-mailbox table-bordered table-striped table-responsive table-condensed">
                        <thead>
                            <tr>
                                <th>XML</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $dir = Yii::getAlias('@runtime/xml/history');
                            if ($handle = opendir($dir)) {
                                while (false !== ($entry = readdir($handle))) {
                                    if ($entry != "." && $entry != "..") {
                                        ?>
                                        <tr>
                                            <td><a href="<?php echo Url::to(['manager/download-file', 'file' => $entry]) ?>" download target="_blank"><?php echo $entry; ?></a></td>
                                            <td style="width: 10%;">
                                                <?php
                                                echo Html::a('<span class="btn btn-danger tbn-sm">Delete</span>', Url::to(['manager/remove-file', 'file' => $entry]), [
                                                    'title' => Yii::t('app', 'Delete'),
                                                    'data-confirm' => 'Are you sure you want to delete this item?',
                                                    'data-method' => 'POST'
                                                ]);
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                closedir($handle);
                            }
                            ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section><!-- /.content -->
