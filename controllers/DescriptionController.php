<?php

namespace app\controllers;

use Yii;

class DescriptionController extends \app\components\CustomController {

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
        $queryString = Yii::$app->request->getQueryString();
        $params = \Yii::$app->request->get();

        $columnConvertion = [
            'status' => 'enabled',
            'publisher' => 'tbl_xml_publisher_id_publisher',
            'segment' => 'tbl_xml_segment_id_segment',
        ];

        $model = \app\models\Description::find();

        $count = 0;
        foreach ($params as $key => $value) {
            if ($key != 'id') {
                if ($count == 0) {
                    $model->where("{$columnConvertion[$key]}=:{$key}", [":$key" => $value]);
                } else {
                    $model->andWhere("{$columnConvertion[$key]}=:{$key}", [":$key" => $value]);
                }
            }

            $count++;
        }

        $descriptions = $model->with('tblXmlSegmentIdSegment', 'tblXmlPublisherIdPublisher')->all();

        return $this->render('index', ['descriptions' => $descriptions, 'queryString' => $queryString]);
    }

    public function actionCreate() {
        $queryString = Yii::$app->request->getQueryString();
        $url = \yii\helpers\Url::to(['description/index']);

        if (!empty($queryString)) {
            $url = $url . '?' . $queryString;
        }
        $model = new \app\models\Description();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                $this->redirect($url);
            }
        }

        return $this->render('create', ['model' => $model, 'queryString' => $url]);
    }

    public function actionUpdate() {
        $queryString = Yii::$app->request->getQueryString();
        $id = \Yii::$app->request->get('id');
        $url = \yii\helpers\Url::to(['description/index']);

        if (!empty($queryString)) {
            $queryString = preg_replace('/id=\d+&/i', '', $queryString);
            $queryString = preg_replace('/id=\d+/i', '', $queryString);

            if (!empty($queryString)) {
                $url = $url . '?' . $queryString;
            }
        }


        $model = \app\models\Description::find()->where('id_description=:id_description', [':id_description' => $id])->one();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                $this->redirect($url);
            }
        }
        return $this->render('update', ['model' => $model, 'queryString' => $url]);
    }

    public function actionDelete() {
        $queryString = Yii::$app->request->getQueryString();
        $id = \Yii::$app->request->get('id');
        $url = \yii\helpers\Url::to(['description/index']);

        if (!empty($queryString)) {
            $queryString = preg_replace('/id=\d+&/i', '', $queryString);
            $queryString = preg_replace('/id=\d+/i', '', $queryString);

            if (!empty($queryString)) {
                $url = $url . '?' . $queryString;
            }
        }

        $model = \app\models\Description::find()->where('id_description=:id_description', [':id_description' => $id])->one();

        if (!empty($model)) {
            $model->delete();
            \Yii::$app->session->setFlash('success', 'The description was successfully removed');
        } else {
            \Yii::$app->session->setFlash('error', 'Oops! an error ocurred whe try delete the description');
        }

        $this->redirect($url);
    }

    public function actionDetail() {
        $id = \Yii::$app->request->get('id');
        $url = Yii::$app->request->referrer;

        $model = \app\models\Description::find()->where('id_description=:id_description', [':id_description' => $id])->one();


        return $this->render('detail', ['model' => $model, 'queryString' => $url]);
    }

    public function actionGetSegments() {
        $response = \app\models\XmlPublisher::getSegments();
        return $response;
    }

    public function actionDeleteMultipleRecords() {
        $json = \Yii::$app->request->post('items');
        $items = '';
        if (!empty($json)) {
            $items = json_decode($json);
        }
        if (empty($items)) {
            throw new \yii\web\ServerErrorHttpException;
        }

        \Yii::$app->db->createCommand()->delete('tbl_description', ['id_description' => $items])->execute();
    }

}
