<?php

namespace app\controllers;

class DataTableController extends \yii\web\Controller {
    public $layout = 'manager';
    
    public function actionIndex() {
        return $this->render('index');
    }
}
