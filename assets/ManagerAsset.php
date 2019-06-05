<?php

namespace app\assets;

use yii\web\AssetBundle;

class ManagerAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'manager/css/bootstrap.min.css',
        'manager/css/font-awesome.min.css',
        'manager/css/ionicons.min.css',
        'manager/css/AdminLTE.css',
        'manager/vendor/sweetalert-master/dist/sweetalert.css',
        'manager/css/manager.css'
    ];
    public $js = [
        'manager/js/bootstrap.min.js',
        'manager/js/AdminLTE/app.js',
        'manager/js/jqueryCookie/jquery.cookie.js',
        'manager/vendor/sweetalert-master/dist/sweetalert.min.js',
        'manager/js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
