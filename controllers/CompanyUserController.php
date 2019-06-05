<?php

namespace app\controllers;

use Yii;

//use yii\filters\AccessControl;
//use yii\filters\VerbFilter;

class CompanyUserController extends \app\components\CustomController {

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
        $companies = \app\models\CompanyUser::find()->orderBy(['name' => SORT_ASC])->all();
        return $this->render('index', ['companies' => $companies]);
    }

    public function actionCreate() {
        $model = new \app\models\CompanyUser();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
//            $model->tenstreet_company_id = trim(\yii::$app->request->post('tenstreet_company_id', ''));
            $model->imageFile = \yii\web\UploadedFile::getInstance($model, 'imageFile');

            if ($model->imageFile) {
                $model->upload();
            }
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'The <strong>Company</strong> was successfully created.');
                $this->redirect(['company-user/index']);
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate() {
        $id = \Yii::$app->request->get('id');

        $model = \app\models\CompanyUser::find()->where('id_company_user=:id_company_user', [':id_company_user' => $id])->one();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->imageFile = \yii\web\UploadedFile::getInstance($model, 'imageFile');

            if ($model->imageFile) {
                $model->upload();
            }

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'The <strong>Company</strong> was successfully updated.');
                $this->redirect(['company-user/index']);
            }
        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete() {
        $id = \Yii::$app->request->get('id');

        $model = \app\models\CompanyUser::find()->where('id_company_user=:id_company_user', [':id_company_user' => $id])->one();
        $session = \Yii::$app->session;
        try {
            $model->delete();
        } catch (\yii\db\IntegrityException $exc) {
            $message = $exc->getMessage();
            //echo $message;
            //exit;
            if (strpos($message, 'tbl_contact') !== false) {
                $session->setFlash('error', 'The <strong>Company</strong> has contacts, please delete all contacts for this company before continue');
            } else if (strpos($message, 'tbl_media_publisher') !== false) {
                $session->setFlash('error', 'The <strong>Company</strong> has media publishers, please delete all media publishers before continue');
            } else if (strpos($message, 'tbl_user_has_tbl_company_user') !== false) {
                $session->setFlash('error', 'The <strong>Company</strong> has active users, please delete the users before continue');
            } else {
                $session->setFlash('error', 'There was an error, please report to the admin');
            }
        }



//        if (!empty($model->tblUserIdUsers) && count($model->tblUserIdUsers) > 0) {
//            \Yii::$app->session->setFlash('error', 'The <strong>Company</strong> has active users, please delete the users before continue.');
//        } else {
//            if (!empty($model)) {
//                \Yii::$app->session->setFlash('success', 'The <strong>Company</strong> was successfully deleted.');
//                $model->delete();
//            }
//        }

        $this->redirect(['company-user/index']);
    }

}
