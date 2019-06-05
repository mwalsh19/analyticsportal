<?php

namespace app\controllers;

class InsertionOrderController extends \yii\web\Controller {

    public $layout = 'manager';

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function beforeAction($action) {
        if ($action->id == 'error') {
            $this->layout = false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionCreate() {
        $model = new \app\models\InsertionOrder();
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate() {
        $model = new \app\models\InsertionOrder();
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete() {

    }

    public function actionDownload() {

    }

    public function actionDuplicate() {

    }

    public function actionInsertionOrderByPublisher() {
        return $this->render('insertion_order_by_publisher');
    }

}
