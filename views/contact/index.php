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
$canCreate = Yii::$app->user->can('contact/create');
$canUpdate = Yii::$app->user->can('contact/update');
$canDelete = Yii::$app->user->can('contact/delete');
?>
<section class="content-header">
    <h1>
        <span>Manager</span> > <span>Contacts Overview</span>
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
                            <h4>Contacts Overview</h4>
                        </div>
                        <?php
                        if ($canCreate) {
                            ?>
                            <div class="pull-right">
                                <a href="<?= Url::to(['contact/create']); ?>" class="btn btn-primary btn-bg btn-create">
                                    <strong>Add Contact</strong> <i class="glyphicon glyphicon-plus"></i>
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <table class="table table-bordered table-hover table-condensed" id="table">
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
                            <?php
                            if (!empty($contacts)) {
                                for ($index = 0; $index < count($contacts); $index++) {
                                    $contactObject = $contacts[$index];
                                    $id_contact = $contactObject->id_contact;
                                    $publisherObject = $contactObject->tblMediaPublisherIdMediaPublisher;
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo Url::to(['media-publisher/detail', 'publisher' => $publisherObject->id_media_publisher, 'target' => 'contact_overview']) ?>">
                                                <?php echo $publisherObject->name; ?></a>
                                        </td>
                                        <td class="text-bold text-custom-size text-custom-color">
                                            <a href="<?php echo Url::to(['contact/detail', 'contact' => $id_contact]) ?>"><?php echo $contactObject->first_name ?> <?php echo $contactObject->last_name ?></a>
                                        </td>
                                        <td><?php echo empty($contactObject->title) ? 'No available' : $contactObject->title ?></td>
                                        <td class="text-custom-color">
                                            <?php
                                            if (!empty($contactObject->phone)) {
                                                echo $contactObject->phone;
                                                echo!empty($contactObject->ext) ? ' Ext. ' . $contactObject->ext : '';
                                            } else {
                                                echo "No available";
                                            }
                                            ?>
                                        </td>
                                        <td class="text-custom-color"><?php echo empty($contactObject->mobile) ? 'No available' : $contactObject->mobile ?></td>
                                        <td class="text-custom-color"><a href="mailto:<?php echo $contactObject->email ?>"><?php echo $contactObject->email ?></a></td>
                                        <td><?php echo empty($contactObject->time_zone) ? 'No available' : $contactObject->time_zone ?></td>
                                        <td>
                                            <?php
                                            if ($canUpdate) {
                                                ?>
                                                <a href="<?= Url::to(['contact/update', 'id' => $id_contact]) ?>" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                                    <?php
                                                }
                                                ?>
                                                <?php
                                                if ($canDelete) {
                                                    echo Html::a('<i class="glyphicon glyphicon-trash"></i>', ['contact/delete', 'id' => $id_contact], [
                                                        'class' => 'btn btn-danger btn-sm',
                                                        'data' => [
                                                            'confirm' => "Are you sure you want to delete this contact?",
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

<?php
if ($session->hasFlash('isNewRecord')) {
    $id_publisher = $session->getFlash('id_publisher', null);

    $string = 'Your contact has been saved<br>Would you like to add another contact?';
    $url = Url::to(['contact/create', 'publisher' => $id_publisher]);

    echo $this->render('//partials/_overlay', ['string' => $string, 'url' => $url]);
}
?>