<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\ManagerAsset;

ManagerAsset::register($this);
?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <!--[if lt IE 9]>
         <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
         <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
       <![endif]-->
    </head>
    <body class="skin-black">
        <?php $this->beginBody() ?>

        <header class="header">
            <a href="<?php echo Url::to('manager/index'); ?>" class="logo">
                SWIFT PORTAL
            </a>
            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="<?php echo Url::to(['manager/logout']); ?>" class="btn btn-flat" style="padding-bottom: 2px;">
                                Sign out
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <aside class="left-side sidebar-offcanvas">
                <section class="sidebar">
                    <ul class="sidebar-menu">

                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-file"></i>
                                <span>Media Contact</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <!--<li><a href="<?php // echo Url::to(['contact/index'])   ?>"><i class="fa fa-angle-double-right"></i> <span>Contacts</span></a></li>-->
                                <li><a href="<?php echo Url::to(['contact-campaign/index']) ?>"><i class="fa fa-angle-double-right"></i> <span>Campaigns</span></a></li>
                                <li><a href="<?php echo Url::to(['contact-publisher/index']) ?>"><i class="fa fa-angle-double-right"></i> <span>Publishers</span></a></li>
                            </ul>
                        </li>
                        <li class="treeview active">
                            <a href="#">
                                <i class="fa fa-file"></i>
                                <span>XML Automation</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu" style="display: block;">
                                <li><a href="<?php echo Url::to(['xml-automation/review']) ?>"><i class="fa fa-angle-double-right"></i> <span>Generator</span></a></li>
                                <li><a href="<?php echo Url::to(['xml-automation/history']) ?>"><i class="fa fa-angle-double-right"></i> <span>History</span></a></li>
                                <li><a href="<?php echo Url::to(['company/index']) ?>"><i class="fa fa-angle-double-right"></i> <span>Companies</span></a></li>
                                <li><a href="<?php echo Url::to(['publisher/index']) ?>"><i class="fa fa-angle-double-right"></i> <span>Publishers</span></a></li>
                                <li><a href="<?php echo Url::to(['campaign/index']) ?>"><i class="fa fa-angle-double-right"></i> <span>Campaigns</span></a></li>
                                <li><a href="<?php echo Url::to(['segment/index']) ?>"><i class="fa fa-angle-double-right"></i> <span>Segments</span></a></li>
                                <li><a href="<?php echo Url::to(['title/index']) ?>"><i class="fa fa-angle-double-right"></i> <span>Titles</span></a></li>
                                <li><a href="<?php echo Url::to(['description/index']) ?>"><i class="fa fa-angle-double-right"></i> <span>Descriptions</span></a></li>
                                <li><a href="<?php echo Url::to(['url/index']) ?>"><i class="fa fa-angle-double-right"></i> <span>Urls</span></a></li>
                            </ul>
                        </li>
                        <li><a href="<?php echo Url::to(['manager/shorturl']) ?>"><i class="fa fa-th"></i> <span>Google URL Shortner</span></a></li>
                        <li><a href="<?php echo Url::to(['manager/analyticsshorturl']) ?>"><i class="fa fa-th"></i> <span>Google URL Analytics</span></a></li>



                    </ul>
                </section>
            </aside>
            <aside class="right-side">
                <?= $content ?>
            </aside>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
