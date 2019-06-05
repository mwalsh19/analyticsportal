<?php

namespace app\controllers;

//use yii\filters\AccessControl;
//use yii\filters\VerbFilter;

class PermissionController extends \yii\web\Controller {

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
        $auth = \Yii::$app->authManager;
        $permissions = $auth->getPermissions();
        return $this->render('index', ['permissions' => $permissions]);
    }

    public function actionCreate() {
        $model = new \app\models\PermissionForm();
        $auth = \Yii::$app->authManager;

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $permissionExist = $auth->getPermission($model->permission_name);
            if (!empty($permissionExist)) {
                \Yii::$app->session->setFlash('error', '<strong>Oops</strong>, The permission you\'re trying to create already exists');
            } else {
                $permission = $auth->createPermission($model->permission_name);
                if (!empty($model->permission_description)) {
                    $permission->description = $model->permission_description;
                }

                if ($auth->add($permission)) {
                    \Yii::$app->session->setFlash('success', '<strong>Success</strong>, The permission was created successfully');
                    $this->redirect(\yii\helpers\Url::to(['permission/index']));
                } else {
                    \Yii::$app->session->setFlash('error', '<strong>Oops</strong>, The permission could not be created');
                }
            }
        }

        return $this->render('form', ['model' => $model]);
    }

    public function actionUpdate() {
        $permissionParam = \Yii::$app->request->get('permission', '');
        if (empty($permissionParam)) {
            throw new \yii\web\NotFoundHttpException;
        }

        $model = new \app\models\PermissionForm();
        $auth = \Yii::$app->authManager;
        $permission = $auth->getPermission($permissionParam);

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $permission->description = $model->permission_description;

            if ($auth->update($permissionParam, $permission)) {
                \Yii::$app->session->setFlash('success', '<strong>Success</strong>, The permission was updated successfully');
                $this->redirect(\yii\helpers\Url::to(['permission/index']));
            } else {
                \Yii::$app->session->setFlash('error', '<strong>Oops</strong>, The permission could not be updated');
            }
        } else {
            $model->permission_name = $permission->name;
            $model->permission_description = $permission->description;
        }

        return $this->render('form', ['model' => $model]);
    }

    public function actionDelete() {
        $permissionParam = \Yii::$app->request->get('permission', '');
        if (empty($permissionParam)) {
            throw new \yii\web\NotFoundHttpException;
        }

        $auth = \Yii::$app->authManager;
        $permission = $auth->getPermission($permissionParam);
        $allRoles = $auth->getRoles();

        if (empty($allRoles)) {
            if ($auth->remove($permission)) {
                \Yii::$app->session->setFlash('success', '<strong>Success</strong>, The permission was removed successfully');
            } else {
                \Yii::$app->session->setFlash('error', '<strong>Error</strong>, An error has occurred while trying to remove this permission');
            }
        } else {
            $permissionHasParent = false;
            foreach ($allRoles as $roleObject) {
                if ($auth->hasChild($roleObject, $permission)) {
                    $permissionHasParent = true;
                    \Yii::$app->session->setFlash('error', '<strong>Error</strong>, You can not remove the permission, because it is associated to a role');
                }
            }
            if (!$permissionHasParent) {
                if ($auth->remove($permission)) {
                    \Yii::$app->session->setFlash('success', '<strong>Success</strong>, The permission was removed successfully');
                } else {
                    \Yii::$app->session->setFlash('error', '<strong>Error</strong>, An error has occurred while trying to remove this permission');
                }
            }
        }

        $this->redirect(\yii\helpers\Url::to(['permission/index']));
    }

}
