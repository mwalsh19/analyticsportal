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
                    <div class="custom-border"></div>
                    <?php
                    $user = Yii::$app->user;
                    $accessToReporting = false;

                    $permissionsReporting = [
                        'tenstreet/sort-filter',
                        'tenstreet/grouped-by-publisher',
                        'tenstreet/grouped-by-code-type'
                    ];

                    foreach ($permissionsReporting as $permission1) {
                        if ($user->can($permission1)) {
                            $accessToReporting = true;
                            break;
                        }
                    }

                    $accessToXmlAutomation = false;
                    $permissionsXmlAutomation = [
                        'xml-automation/review',
                        'xml-automation/history',
                        'campaign/index',
                        'company/index',
                        'publisher/index',
                        'segment/index',
                        'title/index',
                        'url/index',
                        'description/index',
                    ];

                    foreach ($permissionsXmlAutomation as $permission) {
                        if ($user->can($permission)) {
                            $accessToXmlAutomation = true;
                            break;
                        }
                    }

                    echo \yii\widgets\Menu::widget([
                        'options' => [
                            'class' => 'sidebar-menu'
                        ],
                        'items' => [
                            [
                                'label' => 'Dashboard',
                                'url' => ['manager/index'],
                                'template' => '<a href="{url}" ><i class="fa fa-dashboard"></i> <span>{label}</span></a>',
                                'visible' => Yii::$app->user->can('manager/index')
                            ],
                            [
                                'label' => 'Media Publisher',
                                'url' => ['media-publisher/index'],
                                'template' => '<a href="{url}" ><i class="fa fa-globe"></i> <span>{label}</span></a>',
                                'visible' => Yii::$app->user->can('media-publisher/index')
                            ],
                            [
                                'label' => 'Contacts',
                                'url' => ['contact/index'],
                                'template' => '<a href="{url}" ><i class="fa fa-users"></i> <span>{label}</span></a>',
                                'visible' => Yii::$app->user->can('contact/index')
                            ],
                            [
                                'label' => 'Insertion Order',
                                'url' => ['insertion-order/index'],
                                'template' => '<a href="{url}" ><i class="fa fa-paste"></i> <span>{label}</span></a>',
                                'visible' => Yii::$app->user->can('insertion-order/index')
                            ],
                            [
                                'label' => 'Reporting',
                                'url' => ['#'],
                                'template' => '<a href="javascript:void(0);" ><i class="fa fa-sitemap"></i> <span>{label}</span><i class="fa fa-angle-left pull-right"></i></a>',
                                'options' => [
                                    'class' => (isset($_COOKIE['tenstreetOpen']) && $_COOKIE['tenstreetOpen'] == 'Y') ? 'treeview tenstreet-tree active' : 'treeview tenstreet-tree'
                                ],
                                'submenuTemplate' => '<ul class="treeview-menu">{items}</ul>',
                                'visible' => $accessToReporting,
                                'items' => [
                                    [
                                        'label' => 'Sort & Filter',
                                        'url' => ['tenstreet/sort-filter'],
                                        'template' => '<a href="{url}" ><i class="fa fa-angle-double-right"></i> <span>{label}</span></a>',
                                        'visible' => Yii::$app->user->can('tenstreet/sort-filter')
                                    ],
                                    [
                                        'label' => 'Sort & Filter (Call Source)',
                                        'url' => ['tenstreet/sort-filter-call-source'],
                                        'template' => '<a href="{url}" ><i class="fa fa-angle-double-right"></i> <span>{label}</span></a>',
//                                        'visible' => Yii::$app->user->can('tenstreet/sort-filter-call-source')
                                    ],
                                    [
                                        'label' => 'Grouped by Publisher',
                                        'url' => ['tenstreet/grouped-by-publisher'],
                                        'template' => '<a href="{url}" ><i class="fa fa-angle-double-right"></i> <span>{label}</span></a>',
                                        'visible' => Yii::$app->user->can('tenstreet/grouped-by-publisher')
                                    ],
                                    [
                                        'label' => "Grouped by Publisher (Call Source)",
                                        'url' => ['tenstreet/grouped-by-publisher-call-source'],
                                        'template' => '<a href="{url}" ><i class="fa fa-angle-double-right"></i> <span>{label}</span></a>',
//                                        'visible' => Yii::$app->user->can('tenstreet/grouped-by-publisher-call-source')
                                    ],
                                    [
                                        'label' => 'Media Report by Publisher',
                                        'url' => ['tenstreet/grouped-by-code-type'],
                                        'template' => '<a href="{url}" ><i class="fa fa-angle-double-right"></i> <span>{label}</span></a>',
                                        'visible' => Yii::$app->user->can('tenstreet/grouped-by-code-type')
                                    ],
                                    [
                                        'label' => 'Executive Report',
                                        'url' => ['tenstreet/executive-report'],
                                        'template' => '<a href="{url}" ><i class="fa fa-angle-double-right"></i> <span>{label}</span></a>',
//                                        'visible' => Yii::$app->user->can('tenstreet/executive-report')
                                    ]
                                ]
                            ],
                            [
                                'label' => 'Data Sources',
                                'url' => ['data-source/initial'],
                                'template' => '<a href="{url}" ><i class="fa fa-file"></i> <span>{label}</span></a>',
                                'visible' => Yii::$app->user->can('data-source/initial'),
                            ],
                            [
                                'label' => 'Tenstreet Import',
                                'url' => ['tenstreet/tenstreet-import'],
                                'template' => '<a href="{url}" ><i class="fa fa-upload"></i> <span>{label}</span></a>',
                                'visible' => Yii::$app->user->can('tenstreet/tenstreet-import'),
                            ],
                            [
                                'label' => '',
                                'url' => ['#'],
                                'template' => '<br><div class="custom-border"></div>',
//                                'visible' => Yii::$app->user->can('manager/shorturl') || Yii::$app->user->can('manager/analyticsshorturl') || $accessToXmlAutomation
                            ],
                            [
                                'label' => '',
                                'url' => ['#'],
                                'template' => '<h4 class="custom-title-section">TOOLS</h4>',
                                'visible' => Yii::$app->user->can('manager/shorturl') || Yii::$app->user->can('manager/analyticsshorturl') || $accessToXmlAutomation
                            ],
                            [
                                'label' => 'XML Automation',
                                'url' => ['#'],
                                'template' => '<a href="javascript:void(0);" ><i class="fa fa-code"></i> <span>{label}</span><i class="fa fa-angle-left pull-right"></i></a>',
                                'options' => [
                                    'class' => (isset($_COOKIE['xmlAutomationOpen']) && $_COOKIE['xmlAutomationOpen'] == 'Y') ? 'treeview xml-automation-tree active' : 'treeview xml-automation-tree'
                                ],
                                'submenuTemplate' => '<ul class="treeview-menu">{items}</ul>',
                                'visible' => $accessToXmlAutomation,
                                'items' => [
                                    [
                                        'label' => 'Generator',
                                        'url' => ['xml-automation/review'],
                                        'template' => '<a href = "{url}" ><i class = "fa fa-angle-double-right"></i> <span>{label}</span></a> ',
                                        'visible' => Yii::$app->user->can('xml-automation/review'),
                                    ],
                                    [
                                        'label' => 'History',
                                        'url' => ['xml-automation/history'],
                                        'template' => '<a href = "{url}" ><i class = "fa fa-angle-double-right"></i> <span>{label}</span></a>',
                                        'visible' => Yii::$app->user->can('xml-automation/history'),
                                    ],
                                    [
                                        'label' => 'Companies',
                                        'url' => ['company/index'],
                                        'template' => '<a href = "{url}" ><i class = "fa fa-angle-double-right"></i> <span>{label}</span></a>',
                                        'visible' => Yii::$app->user->can('company/index'),
                                    ],
                                    [
                                        'label' => 'Publishers',
                                        'url' => ['publisher/index'],
                                        'template' => '<a href = "{url}" ><i class = "fa fa-angle-double-right"></i> <span>{label}</span></a>',
                                        'visible' => Yii::$app->user->can('publisher/index'),
                                    ],
                                    [
                                        'label' => 'Campaigns',
                                        'url' => ['campaign/index'],
                                        'template' => '<a href = "{url}" ><i class = "fa fa-angle-double-right"></i> <span>{label}</span></a>',
                                        'visible' => Yii::$app->user->can('campaign/index'),
                                    ],
                                    [
                                        'label' => 'Segments',
                                        'url' => ['segment/index'],
                                        'template' => '<a href = "{url}" ><i class = "fa fa-angle-double-right"></i> <span>{label}</span></a>',
                                        'visible' => Yii::$app->user->can('segment/index'),
                                    ],
                                    [
                                        'label' => 'Titles',
                                        'url' => ['title/index'],
                                        'template' => '<a href = "{url}" ><i class = "fa fa-angle-double-right"></i> <span>{label}</span></a>',
                                        'visible' => Yii::$app->user->can('title/index'),
                                    ],
                                    [
                                        'label' => 'Descriptions',
                                        'url' => ['description/index'],
                                        'template' => '<a href = "{url}" ><i class = "fa fa-angle-double-right"></i> <span>{label}</span></a>',
                                        'visible' => Yii::$app->user->can('description/index'),
                                    ],
                                    [
                                        'label' => 'Urls',
                                        'url' => ['url/index'],
                                        'template' => '<a href = "{url}" ><i class = "fa fa-angle-double-right"></i> <span>{label}</span></a>',
                                        'visible' => Yii::$app->user->can('url/index'),
                                    ]
                                ]
                            ],
                            [
                                'label' => 'Google Url Shortner',
                                'url' => ['manager/shorturl'],
                                'template' => '<a href="{url}" ><i class="fa fa-magic"></i> <span>{label}</span></a>',
                                'visible' => Yii::$app->user->can('manager/shorturl')
                            ],
                            [
                                'label' => 'Google URL Analitycs',
                                'url' => ['manager/analyticsshorturl'],
                                'template' => '<a href="{url}" ><i class="fa fa-bar-chart-o"></i> <span>{label}</span></a>',
                                'visible' => Yii::$app->user->can('manager/analyticsshorturl')
                            ],
                            [
                                'label' => '',
                                'url' => ['#'],
                                'template' => '<div class="custom-border"></div>',
                                'visible' => Yii::$app->user->can('manager/shorturl') || Yii::$app->user->can('manager/analyticsshorturl') || $accessToXmlAutomation
                            ],
                            [
                                'label' => '',
                                'url' => ['#'],
                                'template' => '<h4 class="custom-title-section">ADMINISTRATIVE</h4>',
                                'visible' => Yii::$app->user->can('company-user/index') || Yii::$app->user->can('user/index') || Yii::$app->user->can('roles/index') || Yii::$app->user->can('permission/index')
                            ],
                            [
                                'label' => 'Companies',
                                'url' => ['company-user/index'],
                                'template' => '<a href="{url}" ><i class="fa fa-key"></i> <span>{label}</span></a>',
                                'visible' => Yii::$app->user->can('company-user/index')
                            ],
                            [
                                'label' => 'Users',
                                'url' => ['user/index'],
                                'template' => '<a href="{url}" ><i class="fa fa-key"></i> <span>{label}</span></a>',
                                'visible' => Yii::$app->user->can('user/index')
                            ],
                            [
                                'label' => 'Roles',
                                'url' => ['roles/index'],
                                'template' => '<a href="{url}" ><i class="fa fa-key"></i> <span>{label}</span></a>',
                                'visible' => Yii::$app->user->can('roles/index')
                            ],
                            [
                                'label' => 'Permissions',
                                'url' => ['permission/index'],
                                'template' => '<a href="{url}" ><i class="fa fa-key"></i> <span>{label}</span></a>',
                                'visible' => Yii::$app->user->can('permission/index')
                            ],
                            [
                                'label' => '',
                                'url' => ['#'],
                                'template' => '<div class="custom-border"></div>',
                                'visible' => Yii::$app->user->can('company-user/index') || Yii::$app->user->can('user/index') || Yii::$app->user->can('roles/index') || Yii::$app->user->can('permission/index')
                            ],
                            [
                                'label' => '',
                                'url' => ['#'],
                                'template' => '<a href="http://www.callsource.com/home/reporting-login/" class="custom-link" target="_blank"><img src="/manager/images/call_source.png"></a>'
                            ],
                            [
                                'label' => '',
                                'url' => ['#'],
                                'template' => '<a href="https://dashboard.tenstreet.com/" class="custom-link" target="_blank"><img src="/manager/images/goto_tenstreet_logo.png"></a><br>'
                            ],
                        ]
                    ]);
                    ?>
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
