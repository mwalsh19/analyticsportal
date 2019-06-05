<?php

namespace app\controllers;

class DataSourceController extends \app\components\CustomController {

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

    public function actionInitial() {
        return $this->render('initial');
    }

    public function actionIndex() {
        $sources = null;
        if (\app\models\DataSource::isCallSource()) {
            $sources = \app\models\CallSource::getCallSources();
        }
        return $this->render('index', ['sources' => $sources]);
    }

    public function actionCreate() {
        $isCallSource = \app\models\DataSource::isCallSource();
        $session = \Yii::$app->session;

        $type = \Yii::$app->request->get('type');
        $fromInitial = isset($_GET['fromInitial']) && $_GET['fromInitial'] = 1;
        $fromOverview = isset($_GET['fromOverview']) && $_GET['fromOverview'] = 1;

        $redirectUrl = ['data-source/index'];
        $redirectUrl['type'] = $type;
        if ($fromInitial) {
            $redirectUrl['fromInitial'] = 1;
        }
        if ($fromOverview) {
            $redirectUrl['fromOverview'] = 1;
        }

        $model = null;
        if ($isCallSource) {
            $model = new \app\models\CallSource();
        } else {
            $model = new \app\models\DataSource();
            $model->owner = \Yii::$app->user->id;
        }

        if ($model->load(\Yii::$app->request->post())) {
            if (!$isCallSource && (empty($model->from_hidden_field) && empty($model->to_hidden_field))) {
                $session->setFlash('error', 'Please select a date range');
            } else {
                $model->sourceFile = \yii\web\UploadedFile::getInstance($model, 'sourceFile');
                if ($model->sourceFile) {
                    if (!$isCallSource) {
                        $model->id_company_user = $session['current_company']['id_company_user'];
                    }
                    if ($model->upload()) {

                        if (!$isCallSource) {
                            $totals = \app\models\DataSource::getTotals($model->owner, $model->file);
                            if (!empty($totals)) {
                                $model->total_leads = $totals->total;
                                $model->hires = $totals->hired;
                                $model->attending_academy = $totals->attendingAcademy;
                                $model->conversions = $totals->conversions;
                            }
                        }

                        if ($model->save(false)) {
                            $session->setFlash('success', 'The <strong>file</strong> was successfully uploaded.');
                            $this->redirect($redirectUrl);
                        } else {
                            $session->setFlash('error', 'An error ocurred when try to save the file source.');
                        }
                    } else {
                        $session->setFlash('error', 'An error ocurred when try to upload the file source.');
                    }
                } else {
                    $session->setFlash('error', 'The file source is empty');
                }
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionDelete() {
        $isCallSource = \app\models\DataSource::isCallSource();
        $id_file = \Yii::$app->request->get('id');

        $type = \Yii::$app->request->get('type');
        $fromInitial = isset($_GET['fromInitial']) && $_GET['fromInitial'] = 1;
        $fromOverview = isset($_GET['fromOverview']) && $_GET['fromOverview'] = 1;

        $redirectUrl = ['data-source/index'];
        $redirectUrl['type'] = $type;
        if ($fromInitial) {
            $redirectUrl['fromInitial'] = 1;
        }
        if ($fromOverview) {
            $redirectUrl['fromOverview'] = 1;
        }


        $model = null;
        if (!$isCallSource) {
            $user_session_id = \Yii::$app->user->id;
            $model = \app\models\DataSource::find()->where('id_file=:id_file', [':id_file' => $id_file])->one();
        } else {
            $model = \app\models\CallSource::find()->where('id_call_source=:id_call_source', [':id_call_source' => $id_file])->one();
        }
        if (!empty($model)) {
            if (!$isCallSource) {
                if ($model->owner == $user_session_id) {
                    $file = \Yii::getAlias('@webroot') . '/uploads/data_source/data_source_user_' . $user_session_id . '/' . $model->file;
                    if (@unlink($file)) {
                        \app\models\DataSourceShare::deleteAll('id_file=:id_file', [':id_file' => $id_file]);
                        $model->delete();
                        \Yii::$app->session->setFlash('success', 'The <strong>File</strong> was successfully deleted.');
                    } else {
                        \Yii::$app->session->setFlash('error', 'There was a problem when trye to delete the <strong>file source</strong>.');
                    }
                } else {
                    throw new \yii\web\yii\web\ForbiddenHttpException;
                }
            } else {
                $file = \Yii::getAlias('@webroot') . '/uploads/call_source/' . $model->file;
                if (@unlink($file)) {
                    $model->delete();
                    \Yii::$app->session->setFlash('success', 'The <strong>file source</strong> was successfully deleted.');
                } else {
                    \Yii::$app->session->setFlash('error', 'There was a problem when trye to delete the <strong>file source</strong>.');
                }
            }
        } else {
            \Yii::$app->session->setFlash('error', 'An error occurred while trying to delete the file.');
        }
        $this->redirect($redirectUrl);
    }

    public function actionGetUsers() {
        $id_file = \Yii::$app->request->get('id_file');
        if (empty($id_file)) {
            throw new \yii\web\NotFoundHttpException;
        }

        $usersArray = \app\models\User::find()->where('id_user!=:id_user', [':id_user' => \Yii::$app->user->id])->orderBy('name ASC')->all();
        $sharedUsersArray = \app\models\DataSourceShare::find()->where('id_file=:id_file', [':id_file' => $id_file])->all();

        $users = [];
        if (!empty($usersArray)) {
            $total = count($usersArray);
            for ($index = 0; $index < $total; $index++) {
                $userObject = $usersArray[$index];
                $obj = new \stdClass();
                $obj->id_user = $userObject->id_user;
                $obj->name = $userObject->name;
                $users[] = $obj;
            }
        }

        $sharedUsers = [];
        if (!empty($sharedUsersArray)) {
            $total = count($sharedUsersArray);
            for ($index = 0; $index < $total; $index++) {
                $sharedUserObject = $sharedUsersArray[$index];
                $obj = new \stdClass();
                $obj->id_user = $sharedUserObject->id_user;
                $sharedUsers[] = $obj;
            }
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'users' => $users,
            'sharedUsers' => $sharedUsers
        ];
    }

    public function actionShare() {
        $users = \Yii::$app->request->post('users');
        $id_file = \Yii::$app->request->post('id_file');

        if (empty($id_file)) {
            throw new \yii\web\NotFoundHttpException;
        }

        \app\models\DataSourceShare::deleteAll('id_file=:id_file', [':id_file' => $id_file]);

        $bulkInsertArray = [];
        if (!empty($users)) {
            foreach ($users as $key => $value) {
                $bulkInsertArray[] = [
                    'id_file' => (int) $id_file,
                    'id_user' => (int) $value
                ];
            }
        }
        if (count($bulkInsertArray) > 0) {
            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction();

            try {

                $command = $db->createCommand();
                $command->batchInsert('tbl_data_source_share', ['id_file', 'id_user'], $bulkInsertArray);
                $command->execute();

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }

            exit();
        } else {
            exit();
        }
    }

    public function actionGetDataSources() {
        $dataSources = \app\models\DataSource::getDataSources();
        $user_session_id = \Yii::$app->user->id;
        $total = count($dataSources);
        $monthsArray = \app\components\Utils::getMonthArray();

        $canShare = \Yii::$app->user->can('data-source/share');
        $canDelete = \Yii::$app->user->can('data-source/delete');

        $type = \Yii::$app->request->get('type');
        $fromInitial = isset($_GET['fromInitial']) && $_GET['fromInitial'] = 1;
        $fromOverview = isset($_GET['fromOverview']) && $_GET['fromOverview'] = 1;

        $deleteUrl = ['data-source/delete'];
        $deleteUrl['type'] = $type;
        if ($fromInitial) {
            $deleteUrl['fromInitial'] = 1;
        }
        if ($fromOverview) {
            $deleteUrl['fromOverview'] = 1;
        }


        $items = [];
        for ($index = 0; $index < $total; $index++) {
            $item = $dataSources[$index];

            $htmlTools = '';
            if ($user_session_id == $item['owner']) {
                if ($canShare) {
                    $htmlTools.= "<a href=\"javascript:void(0);\" class=\"btn btn-primary btn-sm share-file-btn\" data-idfile=\"{$item['id_file']}\"><i class=\"glyphicon glyphicon-share\"></i></a>&nbsp;";
                }
                if ($canDelete) {
                    $deleteUrl['id'] = $item['id_file'];
                    $htmlTools.= \yii\helpers\Html::a('<i class="glyphicon glyphicon-trash"></i>', $deleteUrl, [
                                'class' => 'btn btn-danger btn-sm',
                                'data' => [
                                    'confirm' => "Are you sure you want to delete this data source?",
                                    'method' => 'post',
                                ],
                    ]);
                }
            }

            $items[] = [
                empty($item['label']) ? 'N/A' : ucfirst($item['label']),
                (int) $item['total_leads'],
                (int) $item['conversions'],
                (int) $item['hires'],
                (int) $item['attending_academy'],
                (int) $item['owner'] == $user_session_id ? 'Me' : ucfirst($item['name']),
                $monthsArray[$item['month']],
                (int) $item['year'],
                date('M d, Y', strtotime($item['create_date'])),
                $htmlTools
            ];
        }

        $obj = new \stdClass();
        $obj->data = $items;

        header('Content-type: application/json');
        echo json_encode($obj);
    }

    public function actionUpdateTotals() {
        $records = \app\models\DataSource::find()->all();
        if (!empty($records)) {
            $total = count($records);
            for ($index = 0; $index < $total; $index++) {
                $object = $records[$index];

                $totals = \app\models\DataSource::getTotals($object->owner, $object->file);

                if (!empty($totals)) {
                    $object->total_leads = $totals->total;
                    $object->hires = $totals->hired;
                    $object->attending_academy = $totals->attendingAcademy;
                    $object->conversions = $totals->conversions;
                    $object->save(false);
                }
            }
        }
    }

}
