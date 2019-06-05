<?php

namespace app\controllers;

use Yii;

class MediaPublisherController extends \app\components\CustomController {

    public $layout = 'manager';

//    private $actions = [
//        'index',
//        'create',
//        'update',
//        'delete',
//        'change-status',
//        'detail'
//    ];
//    public function behaviors() {
//        $behaviors['access'] = [
//            'class' => AccessControl::className(),
//            'rules' => [
//                [
//                    'allow' => true,
//                    'roles' => ['@'],
//                    'matchCallback' => function ($rule, $action) {
//
//                $action = \Yii::$app->controller->action->id;
//                $controller = \Yii::$app->controller->id;
//                $route = "$controller/$action";
//
//                if (\Yii::$app->user->can($route)) {
//                    return true;
//                }
//            }
//                ],
//            ]
//        ];
//
//        return $behaviors;
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
        $publishers = \app\models\MediaPublisher::find()->orderBy('name ASC')->all();

        return $this->render('index', ['publishers' => $publishers]);
    }

    public function actionCreate() {
        $model = new \app\models\MediaPublisher();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $session = \Yii::$app->session;
            $model->id_company_user = $session['current_company']['id_company_user'];
            $model->imageFile = \yii\web\UploadedFile::getInstance($model, 'imageFile');

            if ($model->imageFile) {
                $model->upload();
            }

            if ($model->save()) {
                $session->setFlash('isNewRecord', 'YES');
                $session->setFlash('id_publisher', $model->id_media_publisher);
                $session->setFlash('success', 'The <strong>Media Publisher</strong> was successfully created.');
                $this->redirect(['media-publisher/index']);
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate() {
        $id = \Yii::$app->request->get('id');
        $model = \app\models\MediaPublisher::find()->where('id_media_publisher=:id_media_publisher', [':id_media_publisher' => $id])->one();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            $model->imageFile = \yii\web\UploadedFile::getInstance($model, 'imageFile');

            if ($model->imageFile) {
                $model->upload();
            }

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'The <strong>Media Publisher</strong> was successfully updated.');
                $this->redirect(['media-publisher/index']);
            }
        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete() {
        $id = \Yii::$app->request->get('id');

        if (!empty($id)) {
            $model = \app\models\MediaPublisher::find()->where('id_media_publisher=:id_media_publisher', [':id_media_publisher' => $id])->one();
            if (!empty($model)) {
                $hasActiveContacts = \app\models\Contact::find()->where('tbl_media_publisher_id_media_publisher=:id_media_publisher', [':id_media_publisher' => $id])->exists();
                if (!$hasActiveContacts) {
                    $model->delete();
                    \Yii::$app->session->setFlash('success', 'The <strong>Media Publisher</strong> was successfully removed.');
                } else {
                    \Yii::$app->session->setFlash('error', 'Ups! the selected media publisher has active contacts, please remove the contacts before delete this media publisher.');
                }
            }
        } else {
            throw new \yii\web\ForbiddenHttpException;
        }

        $this->redirect(['media-publisher/index']);
    }

    public function actionDetail() {
        $id_media_publisher = Yii::$app->request->get('publisher');
        $publisher = \app\models\MediaPublisher::find()->where('id_media_publisher=:id_media_publisher', [':id_media_publisher' => $id_media_publisher])->with('contacts')->one();

        return $this->render('detail', ['publisher' => $publisher]);
    }

    public function actionChangeStatus() {
        $status = \Yii::$app->request->post('status');
        $publisher = \Yii::$app->request->post('publisher');
        $obj = new \stdClass();

        $model = \app\models\MediaPublisher::find()->where('id_media_publisher=:id_media_publisher', [':id_media_publisher' => $publisher])->one();

        if (!empty($model)) {
            $model->status = $status;
            if ($model->save()) {
                $obj->status = 'OK';
            } else {
                $obj->status = 'ERROR';
            }
        } else {
            $obj->status = 'ERROR';
        }

        header("Content-Type: application/json");
        echo json_encode($obj);
    }

}
