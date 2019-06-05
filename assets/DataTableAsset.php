<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DataTableAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'manager/css/datatables/dataTables.bootstrap.css',
//        '//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css'
    ];
    public $js = [
        'manager/js/plugins/datatables/jquery.dataTables.js',
        'manager/js/plugins/datatables/dataTables.bootstrap.js',
//        '//cdn.datatables.net/plug-ins/1.10.11/sorting/custom-data-source/dom-checkbox.js',
//        '//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js'
    ];
    public $depends = [
        'app\assets\ManagerAsset'
    ];

}
