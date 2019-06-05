<?php

namespace app\controllers;

use Yii;

//use yii\filters\AccessControl;
//use yii\filters\VerbFilter;

class CompanyController extends \app\components\CustomController {

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
        $companies = \app\models\Company::find()->orderBy(['name' => SORT_ASC])->all();
        return $this->render('index', ['companies' => $companies]);
    }

    public function actionCreate() {
        $model = new \app\models\Company();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                $this->redirect(['company/index']);
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate() {
        $id = \Yii::$app->request->get('id');

        $model = \app\models\Company::find()->where('id_company=:id_company', [':id_company' => $id])->one();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                $this->redirect(['company/index']);
            }
        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete() {
        $id = \Yii::$app->request->get('id');

        $model = \app\models\Company::find()->where('id_company=:id_company', [':id_company' => $id])->one();

        if (!empty($model)) {
            $model->delete();
        }
        $this->redirect(['company/index']);
    }

}
