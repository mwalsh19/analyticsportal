<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\ManagerAsset;

ManagerAsset::register($this);
$session = \Yii::$app->session;
$currentCompany = $session['current_company'];
$allCompanies = $session['companies'];
$totalCompanies = !empty($allCompanies) ? count($allCompanies) : 0;
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
    <body class="skin-blue">
        <?php $this->beginBody() ?>

        <header class="header">
            <div class="logo">
                <img src="<?php echo $currentCompany['logo']; ?>" class="logo-img">
                <?php
                if ($totalCompanies > 1) {
                    ?>
                    <a href="javascript:void(0);" class="change-user-company"><img src="<?php echo Url::base() ?>/manager/images/icon_dropdown.jpg"></a>
                    <?php
                }
                ?>
                <?php
                if (!empty($allCompanies)) {
                    ?>
                    <div class="user-company-list hide">
                        <?php
                        $totalCompanies = count($allCompanies);

                        for ($index = 0; $index < $totalCompanies; $index++) {
                            $logo = \yii\helpers\Url::base() . '/manager/images/beta_logo.png';
                            $companyObject = $allCompanies[$index];
                            $company = $companyObject->tblCompanyUserIdCompanyUser;
                            if (!empty($company->logo)) {
                                $logo = \yii\helpers\Url::base() . '/uploads/company_logo/' . $company->logo;
                            }

                            echo "<div><a href='javascript:void(0);' data-src='{$logo}' data-id='$company->id_company_user'><img src='{$logo}'></a></div>";
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>
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
                            <a href="<?php echo Url::to(['user/logout']); ?>" class="btn btn-flat" style="padding-bottom: 2px;">
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
                        <li><div class="custom-border"></div></li>
                        <li><a href=""><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                        <li<?php if ($this->context->id == 'media-publisher') { ?> class="active"<?php } ?>><a href="<?php echo Url::to(['media-publisher/index']) ?>"><i class="fa fa-globe"></i> <span>Media Publisher</span></a></li>
                        <li<?php if ($this->context->id == 'contact') { ?> class="active"<?php } ?>><a href="<?php echo Url::to(['contact/index']) ?>"><i class="fa fa-users"></i> <span>Contacts</span></a></li>
                        <li><a href=""><i class="fa fa-paste"></i> <span>Insertion Order</span></a></li>

                        <li class="treeview tenstreet-tree<?php if (isset($_COOKIE['tenstreetOpen']) && $_COOKIE['tenstreetOpen'] == 'Y') { ?> active<?php } ?>">
                            <a href="javascript:void(0);"><i class="fa fa-sitemap"></i> <span>Reporting</span> <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo Url::to(['tenstreet/index']) ?>"><i class="fa fa-angle-double-right"></i> <span>Sort & Filter</span></a></li>
                                <li><a href="<?php echo Url::to(['tenstreet/report1']) ?>"><i class="fa fa-angle-double-right"></i> <span>Grouped by Publisher</span></a></li>
                                <li><a href="<?php echo Url::to(['tenstreet/report2']) ?>"><i class="fa fa-angle-double-right"></i> <span>Report 2</span></a></li>
                            </ul>
                        </li>

                        <li><a href="<?php echo Url::to(['data-source/index']) ?>"><i class="fa fa-file"></i> <span>Data Sources</span></a></li>
                        <li><a href="<?php echo Url::to(['tenstreet/tenstreet-import']) ?>"><i class="fa fa-upload"></i> <span>Tenstreet Import</span></a></li>
                        <br>
                        <li><div class="custom-border"></div></li>
                        <li><h4 class="custom-title-section">TOOLS</h4></li>

                        <li class="treeview xml-automation-tree<?php if (isset($_COOKIE['xmlAutomationOpen']) && $_COOKIE['xmlAutomationOpen'] == 'Y') { ?> active<?php } ?>">
                            <a href="javascript:void(0);">
                                <i class="fa fa-code"></i>
                                <span>XML Automation</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
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

                        <li><a href="<?php echo Url::to(['manager/shorturl']) ?>"><i class="fa fa-magic"></i> <span>Google Url Shortner</span></a></li>
                        <li><a href="<?php echo Url::to(['manager/analyticsshorturl']) ?>"><i class="fa fa-bar-chart-o"></i> <span>Google URL Analitycs</span></a></li>
                        <li><div class="custom-border"></div></li>
                        <li><h4 class="custom-title-section">ADMINISTRATIVE</h4></li>
                        <li><a href="<?php echo Url::to(['company-user/index']) ?>"><i class="fa fa-key"></i> <span>Companies</span></a></li>
                        <li><a href="<?php echo Url::to(['user/index']) ?>"><i class="fa fa-key"></i> <span>Users</span></a></li>
                        <li><a href="<?php echo Url::to(['roles/index']) ?>"><i class="fa fa-key"></i> <span>Roles</span></a></li>
                        <li><a href="<?php echo Url::to(['permission/index']) ?>"><i class="fa fa-key"></i> <span>Permissions</span></a></li>
                        <li><div class="custom-border"></div></li>
                        <li><a href="" class="custom-link"><img src="<?php echo Url::base() ?>/manager/images/real_time.png"></a></li>
                        <li><a href="http://www.callsource.com/home/reporting-login/" class="custom-link" target="_blank"><img src="/manager/images/call_source.png"></a>
                        </li>
                        <li><a href="https://dashboard.tenstreet.com/" class="custom-link" target="_blank"><img src="/manager/images/goto_tenstreet_logo.png"></a>
                            <br>
                        </li>
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
