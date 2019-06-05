<?php

namespace app\controllers;

use Yii;

class PublisherController extends \app\components\CustomController {

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
        $publishers = \app\models\XmlPublisher::find()->with('tblCompanyIdCompany')->all();

        return $this->render('index', ['publishers' => $publishers]);
    }

    public function actionCreate() {
        $model = new \app\models\XmlPublisher();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                $this->redirect(['publisher/index']);
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate() {
        $id = \Yii::$app->request->get('id');
        $model = \app\models\XmlPublisher::find()->where('id_publisher=:id_publisher', [':id_publisher' => $id])->one();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                $this->redirect(['publisher/index']);
            }
        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete() {
        $id = \Yii::$app->request->get('id');

        if (!empty($id)) {
            $model = \app\models\XmlPublisher::find()->where('id_publisher=:id_publisher', [':id_publisher' => $id])->one();
            if (!empty($model)) {
                $hasActiveContacts = \app\models\Contact::find()->where('tbl_publisher_id_publisher=:id_publisher', [':id_publisher' => $id])->exists();
                if (!$hasActiveContacts) {
                    $model->delete();
                } else {
                    \Yii::$app->session->setFlash('error', 'Ups! the selected publisher has active contacts, please remove the contacts before delete this publisher.');
                }
            }
        } else {
            throw new \yii\web\ForbiddenHttpException;
        }

        $this->redirect(['publisher/index']);
    }

}
