<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$basePath = Url::base();
$this->registerJsFile(Url::base() . '/manager/js/contacts.js', ['position' => \yii\web\View::POS_END, 'depends' => \app\assets\ManagerAsset::className()]);
$publisherObject = $contact->tblMediaPublisherIdMediaPublisher;
?>
<section class = "content-header">
    <h1 class>
        <span>Manager > </span><span class="blue-span"><a href="<?php echo Url::to(['contact/index']); ?>">Contacts Overview</a> > </span><span><?php echo ucfirst($contact->first_name) . ' ' . ucfirst($contact->last_name); ?></span>
    </h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-9">
            <!--publiser info-->
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="publisher-info">
                                <h3 class="publisher-name"><?php echo $publisherObject->name; ?></h3>
                                <?php
                                if (!empty($publisherObject->address)) {
                                    ?>
                                    <div class="publisher-address">
                                        <?php echo $publisherObject->address; ?>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="publisher-secondary-info">
                                    <?php
                                    if (!empty($publisherObject->phone_number)) {
                                        ?>
                                        <div class="publisher-phone"><i class="fa fa-phone"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span class="blue-span"><?php echo $publisherObject->phone_number; ?></span></div>
                                        <?php
                                    }

                                    if (!empty($publisherObject->website_url)) {
                                        ?>
                                        <div class="publisher-email"><i class="fa fa-laptop"></i>&nbsp;&nbsp;&nbsp;<a href="<?php echo $publisherObject->website_url; ?>" target="_blank"><span class="blue-span"><?php echo $publisherObject->website_url; ?></a></span></div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <?php
                            $logoSource = '/manager/images/logo.png';
                            if (!empty($publisherObject->logo)) {
                                $logoSource = '/uploads/publisher_logo/' . $publisherObject->logo;
                            }
                            ?>
                            <div class="publisher-logo"><img src="<?php echo $basePath . $logoSource ?>" class="img-responsive"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!--contacts-->
            <div class="box">
                <div class="box-body">
                    <div class="contacts contact-detail">
                        <div class="clearfix">
                            <div class="contact-avatar style-2">
                                <?php
                                $avatar = 'avatar.png';
                                if (!empty($contact->photo)) {
                                    $avatar = $contact->photo;
                                }
                                ?>
                                <?php app\components\Utils::getAvatarImage($avatar, '110px', 15); ?>
                            </div>
                            <div class="contact-info">
                                <div class="contact-name"><?php echo $contact->first_name; ?> <?php echo $contact->last_name; ?></div>
                                <div class="contact-title"><?php echo $contact->title; ?></div>
                                <div class="contact-timezone"><?php echo $contact->time_zone; ?></div>
                            </div>
                            <div class="contact-secondary-info style-2">
                                <?php
                                if (!empty($contact->phone)) {
                                    ?>
                                    <div class="contact-phone"><i class="fa fa-phone"></i>&nbsp;&nbsp;&nbsp;<span class="blue-span"><?php echo $contact->phone; ?><?php echo!empty($contact->ext) ? ' Ext. ' . $contact->ext : ''; ?></span></div>
                                    <?php
                                }
                                if (!empty($contact->mobile)) {
                                    ?>
                                    <div class="contact-cellphone"><i class="glyphicon glyphicon-phone"></i>&nbsp;&nbsp;<span class="blue-span"><?php echo $contact->mobile; ?></span></div>
                                    <?php
                                }
                                if (!empty($contact->email)) {
                                    ?>
                                    <div class="contact-email"><i class="fa fa-envelope-o"></i>&nbsp;&nbsp;&nbsp;<a href="mailto:<?php echo $contact->email; ?>"><span class="blue-span"><?php echo $contact->email; ?></span></a></div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                        if (!empty($contact->notes)) {
                            ?>
                            <div class="more-info-container">
                                <br>
                                <div class="callout callout-info">
                                    <h4>About <?php echo ucfirst($contact->first_name); ?></h4>
                                    <p><?php echo $contact->notes; ?></p>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
