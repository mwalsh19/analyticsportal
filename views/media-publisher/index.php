<?php
/* @var $this yii\web\View */

use app\assets\DataTableAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Alert;

DataTableAsset::register($this);

$this->registerJs("
     $('#table').dataTable();

     $(document).on('click', '.status-radio-button', function(){
        var checkboxVal = 0;
        var publisher = $(this).data('publisher');
        if($(this).is(':checked')){
            checkboxVal =1;
        }
        $.post('change-status',{publisher: publisher, status: checkboxVal}, function(){

        }).fail(function(){

        });
    });
");
?>
<section class="content-header">
    <h1>
        <span>Manager</span> > <span>Media Publisher Overview</span>
    </h1>
    <?php
    $session = Yii::$app->session;
    if ($session->hasFlash('error')) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-danger',
            ],
            'body' => $session->getFlash('error'),
        ]);
    }
    if ($session->hasFlash('success')) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success',
            ],
            'body' => $session->get('success'),
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
                            <a href="<?= Url::to(['media-publisher/create']); ?>" class="btn btn-primary btn-bg btn-create">
                                <strong>Add Publisher</strong> <i class="glyphicon glyphicon-plus"></i>
                            </a>
                        </div>
                    </div>
                    <br>
                    <table class="table table-bordered table-hover table-striped" id="table">
                        <thead>
                            <tr>
                                <th class="text-bold text-custom-size text-custom-color">Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Zip</th>
                                <th>Active</th>
                                <th>Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($publishers)) {
                                $count = 1;
                                for ($index = 0; $index < count($publishers); $index++) {
                                    $id_media_publisher = $publishers[$index]->id_media_publisher;
                                    ?>
                                    <tr class="contact-row" data-id="<?php echo $id_media_publisher; ?>">
                                        <td class="text-bold text-custom-size text-custom-color">
                                            <a href="<?php echo Url::to(['media-publisher/detail', 'publisher' => $id_media_publisher]) ?>"><?php echo!empty($publishers[$index]->name) ? $publishers[$index]->name : '' ?></a>
                                        </td>
                                        <td><?php echo!empty($publishers[$index]->phone_number) ? $publishers[$index]->phone_number : 'No Available' ?></td>
                                        <td><?php echo!empty($publishers[$index]->address) ? $publishers[$index]->address : 'No Available' ?></td>
                                        <td><?php echo!empty($publishers[$index]->city) ? $publishers[$index]->city : 'No Available' ?></td>
                                        <td><?php echo!empty($publishers[$index]->state) ? $publishers[$index]->state : 'No Available' ?></td>
                                        <td><?php echo!empty($publishers[$index]->zip_code) ? $publishers[$index]->zip_code : 'No Available' ?></td>
                                        <td><input type="checkbox" class="status-radio-button" data-publisher="<?php echo $id_media_publisher ?>"  <?php echo $publishers[$index]->status ? 'checked' : '' ?>></td>
                                        <td>
                                            <a href="<?= Url::to(['media-publisher/update', 'id' => $id_media_publisher]) ?>" class="btn btn-success btn-sm">
                                                <i class="glyphicon glyphicon-edit"></i>
                                            </a>
                                            <?php
                                            echo Html::a('<i class="glyphicon glyphicon-trash"></i>', ['media-publisher/delete', 'id' => $id_media_publisher], [
                                                'class' => 'btn btn-danger btn-sm',
                                                'data' => [
                                                    'confirm' => "Are you sure to delete this publisher?",
                                                    'method' => 'post',
                                                ],
                                            ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $count++;
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

<?php
if ($session->hasFlash('isNewRecord') && $session->hasFlash('id_publisher')) {
    $id_publisher = $session->getFlash('id_publisher', null);

    $string = 'Your publisher has been saved<br>Would you like to start adding contacts?';
    $url = Url::to(['contact/create', 'publisher' => $id_publisher]);

    echo $this->render('//partials/_overlay', ['string' => $string, 'url' => $url]);
}
?>