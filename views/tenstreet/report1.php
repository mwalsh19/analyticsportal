<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$basePath = Url::base();
$params = [
    'position' => yii\web\View::POS_END,
    'depends' => app\assets\ManagerAsset::className()
];
//$this->registerCssFile($basePath . '/manager/css/datatables/dataTables.bootstrap.css');
//$this->registerJsFile($basePath . '/manager/js/plugins/datatables/jquery.dataTables.js', $params);
//$this->registerJsFile($basePath . '/manager/js/plugins/datatables/dataTables.bootstrap.js', $params);
$this->registerJsFile($basePath . '/manager/js/report1.js', $params);
?>

<section class="content-header">
  <h1>
    <span>Tenstreet > </span></span> Data Sources
  </h1>
</section>
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-body">
          <div class="clearfix">
              <?php
              if (!empty($dataSources)) {
                  $items = [];
                  $total = count($dataSources);
                  for ($index = 0; $index < $total; $index++) {
                      $dataSourceArray = $dataSources[$index];
                      $items[$dataSourceArray['owner'] . '_' . $dataSourceArray['id_file']] = !empty($dataSourceArray['label'])
                                  ? $dataSourceArray['label'] : $dataSourceArray['file'];
                  }

                  echo yii\helpers\Html::dropDownList('data-source-select', null, $items, [
                      'prompt' => '-- Select a Data Source -- ',
                      'style' => 'max-width: 320px; display: inline-block;',
                      'class' => 'form-control'
                  ]);
                  echo "<br>";
              } else {
                  echo "<h4>You do not have data sources available, please upload a data source to continue.</h4>";
              }
              ?>
            <span class="customDateVal">&nbsp;<strong></strong></span>
          </div>
          <div style="position: relative; min-height: 280px;">
            <div id="tables-remote"></div>
            <div id="loading" style="display: none;"><div class='uil-reload-css' style='-webkit-transform:scale(0.6)'><div></div></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
