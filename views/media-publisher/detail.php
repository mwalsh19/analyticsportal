<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$basePath = \yii\helpers\Url::base();
$this->registerJsFile(Url::base() . '/manager/js/contacts.js', ['position' => \yii\web\View::POS_END, 'depends' => \app\assets\ManagerAsset::className()]);
?>
<section class = "content-header">
    <h1 class>
        <span>Manager > </span><span class="blue-span"><a href="<?php echo!empty($_GET['target']) ? Url::to(['contact/index']) : Url::to(['media-publisher/index']); ?>"><?php echo!empty($_GET['target']) ? 'Contacts' : 'Media Publisher' ?> Overview</a> > </span><span><?php echo!empty($publisher->name) ? ucfirst($publisher->name) : 'Publisher Name'; ?></span>
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
                                <h3 class="publisher-name"><?php echo $publisher->name; ?></h3>
                                <?php
                                if (!empty($publisher->address)) {
                                    ?>
                                    <div class="publisher-address">
                                        <?php echo $publisher->address; ?>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="publisher-secondary-info">
                                    <?php
                                    if (!empty($publisher->phone_number)) {
                                        ?>
                                        <div><i class="fa fa-phone"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span class="blue-span"><?php echo $publisher->phone_number; ?></span></div>
                                        <?php
                                    }

                                    if (!empty($publisher->website_url)) {
                                        ?>
                                        <div><i class="fa fa-laptop"></i>&nbsp;&nbsp;&nbsp;<a href="<?php echo $publisher->website_url; ?>" target="_blank"><span class="blue-span"><?php echo $publisher->website_url; ?></span></a></div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <?php
                            $logoSource = '/manager/images/logo.png';
                            if (!empty($publisher->logo)) {
                                $logoSource = '/uploads/publisher_logo/' . $publisher->logo;
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
                    <h4 class="contacts-title"><?php echo $publisher->name; ?> Contacts</h4>
                    <?php
                    if (!empty($publisher->contacts)) {
                        ?>
                        <div class="contacts publisher-detail">
                            <?php
                            $contacts = $publisher->contacts;
                            $total = count($contacts);
                            for ($index = 0; $index < $total; $index++) {
                                $contactObject = $contacts[$index];
                                ?>
                                <div class="box box-primary">
                                    <div class="box-body">
                                        <div class="clearfix <?php echo!empty($contactObject->notes) ? 'trigger-more-info' : '' ?>">
                                            <div class="contact-avatar">
                                                <?php
                                                $avatar = 'avatar.png';
                                                if (!empty($contactObject->photo)) {
                                                    $avatar = $contactObject->photo;
                                                }
                                                app\components\Utils::getAvatarImage($avatar, '60px', 15);
                                                ?>
                                            </div>
                                            <div class="contact-info">
                                                <div class="contact-name"><?php echo $contactObject->first_name; ?> <?php echo $contactObject->last_name; ?></div>
                                                <div class="contact-title"><?php echo $contactObject->title; ?></div>
                                                <div class="contact-timezone"><?php echo $contactObject->time_zone; ?></div>
                                            </div>
                                            <div class="contact-secondary-info">
                                                <?php
                                                if (!empty($contactObject->phone)) {
                                                    ?>
                                                    <div><i class="fa fa-phone"></i>&nbsp;&nbsp;&nbsp;<span class="blue-span"><?php echo $contactObject->phone; ?><?php echo!empty($contactObject->ext) ? ' Ext. ' . $contactObject->ext : ''; ?></span></div>
                                                    <?php
                                                }

                                                if (!empty($contactObject->mobile)) {
                                                    ?>
                                                    <div><i class="glyphicon glyphicon-phone"></i>&nbsp;&nbsp;<span class="blue-span"><?php echo $contactObject->mobile; ?></span></div>
                                                    <?php
                                                }

                                                if (!empty($contactObject->email)) {
                                                    ?>
                                                    <div><i class="fa fa-envelope-o"></i>&nbsp;&nbsp;&nbsp;<a href="mailto:<?php echo $contactObject->email; ?>"><span class="blue-span"><?php echo $contactObject->email; ?></span></a></div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                        if (!empty($contactObject->notes)) {
                                            ?>
                                            <div class="more-info-container hide">
                                                <br>
                                                <div class="callout callout-info">
                                                    <h4>About <?php echo ucfirst($contactObject->first_name); ?></h4>
                                                    <p><?php echo $contactObject->notes; ?></p>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                        </div>
                        <?php
                    } else {
                        echo "<p>Contacts no available</p>";
                    }
                    ?>

                </div>
            </div>

        </div>
    </div>
</section>
