<?php
$this->registerCssFile('https://cdn.datatables.net/t/dt/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.11,b-1.1.2,b-colvis-1.1.2,b-flash-1.1.2,b-html5-1.1.2,b-print-1.1.2,cr-1.3.1,r-2.0.2/datatables.min.css');
$this->registerJsFile('https://cdn.datatables.net/t/dt/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.11,b-1.1.2,b-colvis-1.1.2,b-flash-1.1.2,b-html5-1.1.2,b-print-1.1.2,cr-1.3.1,r-2.0.2/datatables.min.js',
        ['depends' => [\yii\web\JqueryAsset::className()]], 'datatables-new');

$this->registerJsFile('/manager/js/datatable-example.js',
        ['depends' => [\yii\web\JqueryAsset::className(), 'datatables-new']]);
$this->title = 'Examples';

echo $this->render('_table');
