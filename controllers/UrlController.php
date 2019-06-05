<?php

namespace app\controllers;

use Yii;

class UrlController extends \app\components\CustomController {

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

        $model = \app\models\Url::find();

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

        $urls = $model->with('tblXmlSegmentIdSegment', 'tblXmlPublisherIdPublisher')->all();

        return $this->render('index', ['urls' => $urls, 'queryString' => $queryString]);
    }

    public function actionCreate() {
        $queryString = Yii::$app->request->getQueryString();
        $urlTarget = \yii\helpers\Url::to(['url/index']);

        if (!empty($queryString)) {
            $urlTarget = $urlTarget . '?' . $queryString;
        }
        $model = new \app\models\Url();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $urlsString = trim($model->url);
            $elements = explode("\n", $urlsString);
            $urlArray = array_filter($elements, 'trim');

            $command = \Yii::$app->db->createCommand();
            $urlsToInsert = [];

            foreach ($urlArray as $url) {
                $trimUrl = trim($url);
                $urlExists = \app\models\Url::find()->where('url=:url AND tbl_xml_segment_id_segment=:id_segment AND tbl_xml_publisher_id_publisher=:id_publisher', [
                            ":url" => $trimUrl,
                            ":id_segment" => $model->tbl_xml_segment_id_segment,
                            ":id_publisher" => $model->tbl_xml_publisher_id_publisher
                        ])->exists();

                if (!$urlExists) {
                    $urlsToInsert[] = [
                        'url' => $trimUrl,
                        'tbl_xml_segment_id_segment' => $model->tbl_xml_segment_id_segment,
                        'tbl_xml_publisher_id_publisher' => $model->tbl_xml_publisher_id_publisher,
                    ];
                }
            }

            if (count($urlsToInsert) > 0) {
                $command->batchInsert('tbl_url', ['url', 'tbl_xml_segment_id_segment', 'tbl_xml_publisher_id_publisher'], $urlsToInsert)->execute();
            }
            $this->redirect($urlTarget);
        }
        return $this->render('create', ['model' => $model, 'queryString' => $urlTarget]);
    }

    public function actionUpdate() {
        $queryString = Yii::$app->request->getQueryString();
        $id = \Yii::$app->request->get('id');
        $url = \yii\helpers\Url::to(['url/index']);

        if (!empty($queryString)) {
            $queryString = preg_replace('/id=\d+&/i', '', $queryString);
            $queryString = preg_replace('/id=\d+/i', '', $queryString);

            if (!empty($queryString)) {
                $url = $url . '?' . $queryString;
            }
        }

        $model = \app\models\Url::find()->where('id_url=:id_url', [':id_url' => $id])->one();

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
        $url = \yii\helpers\Url::to(['url/index']);

        if (!empty($queryString)) {
            $queryString = preg_replace('/id=\d+&/i', '', $queryString);
            $queryString = preg_replace('/id=\d+/i', '', $queryString);

            if (!empty($queryString)) {
                $url = $url . '?' . $queryString;
            }
        }

        $model = \app\models\Url::find()->where('id_url=:id_url', [':id_url' => $id])->one();

        if (!empty($model)) {
            $model->delete();
            \Yii::$app->session->setFlash('success', 'The url was successfully removed');
        } else {
            \Yii::$app->session->setFlash('error', 'Oops! an error ocurred whe try delete the url');
        }
        $this->redirect($url);
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

        \Yii::$app->db->createCommand()->delete('tbl_url', ['id_url' => $items])->execute();
    }

}
