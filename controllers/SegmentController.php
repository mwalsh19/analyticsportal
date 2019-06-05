<?php

namespace app\controllers;

use Yii;

class SegmentController extends \app\components\CustomController {

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
        $segments = \app\models\XmlSegment::find()->orderBy('name ASC')->all();

        return $this->render('index', ['segments' => $segments]);
    }

    public function actionCreate() {
        $request = Yii::$app->request;
        $model = new \app\models\XmlSegment();

        if ($model->load($request->post()) && $model->validate()) {
            if ($model->save()) {
//                $publisherToInsert = [];
//                $command = \Yii::$app->db->createCommand();
//
//                foreach ($model->publishers as $id_publisher) {
//                    $publisherToInsert[] = [
//                        'tbl_xml_segment_id_segment' => $model->id_segment,
//                        'tbl_xml_publisher_id_publisher' => $id_publisher
//                    ];
//                }
//
//                if (count($publisherToInsert) > 0) {
//                    $command->batchInsert('tbl_xml_segment_has_tbl_xml_publisher', [
//                        'tbl_xml_segment_id_segment', 'tbl_xml_publisher_id_publisher'
//                            ], $publisherToInsert)->execute();
//                }
                $this->redirect(['segment/index']);
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate() {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = \app\models\XmlSegment::find()->where('id_segment=:id_segment', [':id_segment' => $id])->with('tblXmlPublisherIdPublishers')->one();

        if ($model->load($request->post()) && $model->validate()) {
            if ($model->save()) {

//                \app\models\base\XmlSegmentHasTblXmlPublisher::deleteAll('tbl_xml_segment_id_segment=:id_segment', [
//                    ':id_segment' => $model->id_segment
//                ]);
//
//                $publisherToInsert = [];
//                $command = \Yii::$app->db->createCommand();
//
//                foreach ($model->publishers as $id_publisher) {
//                    $publisherToInsert[] = [
//                        'tbl_xml_segment_id_segment' => $model->id_segment,
//                        'tbl_xml_publisher_id_publisher' => $id_publisher
//                    ];
//                }
//
//                if (count($publisherToInsert) > 0) {
//                    $command->batchInsert('tbl_xml_segment_has_tbl_xml_publisher', [
//                        'tbl_xml_segment_id_segment', 'tbl_xml_publisher_id_publisher'
//                            ], $publisherToInsert)->execute();
//                }

                $this->redirect(['segment/index']);
            }
        }


        if (!empty($model->tblXmlPublisherIdPublishers)) {
            $publishers = [];

            for ($index = 0; $index < count($model->tblXmlPublisherIdPublishers); $index++) {
                $publisher = $model->tblXmlPublisherIdPublishers[$index];
                $publishers[] = $publisher->id_publisher;
            }

            $model->publishers = $publishers;
        }


        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete() {
        $id = \Yii::$app->request->get('id');

        if (!empty($id)) {
            $model = \app\models\XmlSegment::find()->where('id_segment=:id_segment', [':id_segment' => $id])->with('descriptions', 'titles', 'urls')->one();
            if (!empty($model)) {
                $hasDependencies = false;

                if (!empty($model['descriptions'])) {
                    $hasDependencies = true;
                }
                if (!empty($model['titles'])) {
                    $hasDependencies = true;
                }
                if (!empty($model['urls'])) {
                    $hasDependencies = true;
                }
                if (!$hasDependencies) {
                    \app\models\base\XmlSegmentHasTblXmlPublisher::deleteAll('tbl_xml_segment_id_segment=:id_segment', [
                        ':id_segment' => $model->id_segment
                    ]);
                    $model->delete();
                } else {
                    Yii::$app->session->setFlash('error', 'Ups!, The selected segment has descriptions OR titles OR urls relationed, please check before delete.');
                }
            }
        } else {
            throw new \yii\web\ForbiddenHttpException;
        }

        $this->redirect(['segment/index']);
    }

    public function actionPublisherChange() {
        $id_publisher = Yii::$app->request->post('id_publisher');
        $id_segment = Yii::$app->request->post('id_segment');
        $is_checked = Yii::$app->request->post('isChecked');

        $obj = new \stdClass();

        if (!empty($id_publisher) && !empty($id_segment)) {
            $status = 1;

            if ($is_checked == 'N') {
                $status = 0;

                \app\models\base\XmlSegmentHasTblXmlPublisher::deleteAll('tbl_xml_publisher_id_publisher=:id_publisher AND tbl_xml_segment_id_segment=:id_segment', [
                    ':id_publisher' => $id_publisher,
                    ':id_segment' => $id_segment
                ]);
            }

            if ($is_checked == 'Y') {
                $model_to_save = new \app\models\base\XmlSegmentHasTblXmlPublisher();
                $model_to_save->tbl_xml_segment_id_segment = $id_segment;
                $model_to_save->tbl_xml_publisher_id_publisher = $id_publisher;

                if ($model_to_save->save()) {
                    $obj->status = 'OK';
                    $obj->msg = 'Publisher was update successfully';
                } else {
                    $obj->status = 'ERROR';
                    $obj->msg = 'Can\'t update the selected publisher.';
                }
            }

            \app\models\SessionStorage::updateAll(['status' => $status], 'tbl_xml_publisher_id_publisher=:id_publisher AND tbl_xml_segment_id_segment=:id_segment', [
                ':id_publisher' => $id_publisher,
                ':id_segment' => $id_segment
            ]);

            header('Content-Type: application/json');
            echo json_encode($obj);
        } else {
            throw new \yii\web\ForbiddenHttpException;
        }
    }

}
