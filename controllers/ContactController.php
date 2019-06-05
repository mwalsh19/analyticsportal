<?php

namespace app\controllers;

use Yii;

class ContactController extends \app\components\CustomController {

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
        $contacts = \app\models\Contact::find()->leftJoin('tbl_media_publisher', 'tbl_media_publisher.id_media_publisher=tbl_contact.tbl_media_publisher_id_media_publisher')->with('tblMediaPublisherIdMediaPublisher')->orderBy('tbl_media_publisher.name ASC')->all();

        return $this->render('index', ['contacts' => $contacts]);
    }

    public function actionCreate() {
        $model = new \app\models\Contact();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $session = \Yii::$app->session;
            $model->imageFile = \yii\web\UploadedFile::getInstance($model, 'imageFile');
            $model->id_company_user = $session['current_company']['id_company_user'];

            if ($model->imageFile) {
                $model->upload();
            }
            if ($model->save()) {
                $session->setFlash('isNewRecord', 'YES');
                $session->setFlash('id_publisher', $model->tbl_media_publisher_id_media_publisher);
                $session->setFlash('success', 'The <strong>Contact</strong> was successfully created.');
                $this->redirect(['contact/index']);
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate() {
        $id = \Yii::$app->request->get('id');
        $model = \app\models\Contact::find()->where('id_contact=:id_contact', [':id_contact' => $id])->one();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            $model->imageFile = \yii\web\UploadedFile::getInstance($model, 'imageFile');

            if ($model->imageFile) {
                $model->upload();
            }

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'The <strong>Contact</strong> was successfully updated.');
                $this->redirect(['contact/index']);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete() {
        $id = \Yii::$app->request->get('id');

        if (!empty($id)) {
            $model = \app\models\Contact::find()->where('id_contact=:id_contact', [':id_contact' => $id])->one();
            if (!empty($model)) {
                $model->delete();
                \Yii::$app->session->setFlash('success', 'The <strong>Contact</strong> was successfully deleted.');
            }
        } else {
            throw new \yii\web\ForbiddenHttpException;
        }

        $this->redirect(['contact/index']);
    }

    public function actionDetail() {
        $id_contact = \Yii::$app->request->get('contact');
        $contact = \app\models\Contact::find()->where('id_contact=:id_contact', [':id_contact' => $id_contact])->with('tblMediaPublisherIdMediaPublisher')->one();

        return $this->render('detail', ['contact' => $contact]);
    }

}
