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
class ExcelBuilderAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '//cdn.datatables.net/t/dt/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.11,b-1.1.2,b-colvis-1.1.2,b-flash-1.1.2,b-html5-1.1.2,b-print-1.1.2,cr-1.3.1,r-2.0.2/datatables.min.css'
    ];
    public $js = [
        '//cdnjs.cloudflare.com/ajax/libs/lodash.js/3.10.1/lodash.js',
        '//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js',
        '/manager/vendor/excel-builder/dist/excel-builder.dist.js',
        '/manager/vendor/excel-builder/downloadify/js/swfobject.js',
        '/manager/vendor/excel-builder/downloadify/js/downloadify.min.js',
    ];
    public $depends = [
        'app\assets\ManagerAsset'
    ];

}
