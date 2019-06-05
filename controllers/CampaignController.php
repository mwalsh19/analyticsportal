<?php

namespace app\controllers;

use Yii;

//use yii\filters\AccessControl;
//use yii\filters\VerbFilter;

class CampaignController extends \app\components\CustomController {

    public $layout = 'manager';

//    private $actions = [
//        'index',
//        'create',
//        'update',
//        'delete'
//    ];
//
//    public function behaviors() {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => $this->actions,
//                'rules' => [
//                    [
//                        'actions' => $this->actions,
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                    [
//                        'allow' => true,
//                        'actions' => ['login', 'signup'],
//                        'roles' => ['?'],
//                    ],
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                // 'logout' => ['post'],
//                ],
//            ],
//        ];
//    }

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
        $campaigns = \app\models\XmlCampaign::find()->all();

        return $this->render('index', ['campaigns' => $campaigns]);
    }

    public function actionCreate() {
        $model = new \app\models\XmlCampaign();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                $this->redirect(['campaign/index']);
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate() {
        $id = \Yii::$app->request->get('id');
        $model = \app\models\XmlCampaign::find()->where('id_campaign=:id_campaign', [':id_campaign' => $id])->one();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                $this->redirect(['campaign/index']);
            }
        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete() {
        $id = \Yii::$app->request->get('id');

        if (!empty($id)) {
            $model = \app\models\XmlCampaign::find()->where('id_campaign=:id_campaign', [':id_campaign' => $id])->with('xmlSegmentHasTblXxmlCampaigns')->one();
            if (!empty($model)) {
                $hasActiveRecords = !empty($model->xmlSegmentHasTblXxmlCampaigns) ? $model->xmlSegmentHasTblXxmlCampaigns : '';
                if (empty($hasActiveRecords)) {
                    $model->delete();
                } else {
                    \Yii::$app->session->setFlash('error', 'Ups! the selected campaign is related with some segments, please remove the segments before delete this campaign.');
                }
            }
        } else {
            throw new \yii\web\ForbiddenHttpException;
        }

        $this->redirect(['campaign/index']);
    }

}
