<?php

namespace app\controllers;

//use yii\filters\AccessControl;
//use yii\filters\VerbFilter;

class RolesController extends \app\components\CustomController {

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
//        $auth->removeAll();
        $roles = $auth->getRoles();

        return $this->render('index', ['roles' => $roles]);
    }

    public function actionCreate() {
        $model = new \app\models\RoleForm(['scenario' => 'create']);

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $auth = \Yii::$app->authManager;

            $roleExist = $auth->getRole($model->role_name);

            if (!empty($roleExist)) {
                \Yii::$app->session->setFlash('error', '<strong>Oops</strong>, The role you\'re trying to create already exists');
            } else {
                $role = $auth->createRole($model->role_name);

                if (!empty($model->role_description)) {
                    $role->description = $model->role_description;
                }
                if ($auth->add($role)) {
                    \Yii::$app->session->setFlash('success', '<strong>Success</strong>, The role was created successfully');
                    $this->redirect(\yii\helpers\Url::to(['roles/index']));
                } else {
                    \Yii::$app->session->setFlash('error', '<strong>Oops</strong>, The role could not be created');
                }
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionAssignPermissions() {
        $roleParam = \Yii::$app->request->get('role', '');
        if (empty($roleParam)) {
            throw new \yii\web\NotFoundHttpException;
        }

        $model = new \app\models\RoleForm(['scenario' => 'permissions']);
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole($roleParam);


        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $auth->removeChildren($role);
            //Assign Roles
//                if (!empty($model->roles) && !empty($role)) {
//                    foreach ($model->roles as $childRole) {
//                        $child = $auth->getRole($childRole);
//                        $auth->addChild($role, $child);
//                    }
//                }
            //Assign Permissions
            if (!empty($model->permissions) && !empty($role)) {
                foreach ($model->permissions as $permission) {
                    $childPermission = $auth->getPermission($permission);
                    $auth->addChild($role, $childPermission);
                }
            }
            \Yii::$app->session->setFlash('success', '<strong>Success</strong>, The permissions were assigned successfully!');
            $this->redirect(\yii\helpers\Url::to(['roles/index']));
        }
        /*
         * GET ALL ROLES
         */
//        $roles = $auth->getRoles();
//        $rolesArray = [];
//
//        if (!empty($roles)) {
//            foreach ($roles as $key => $value) {
//                if ($key !== $roleParam) {
//                    $rolesArray[$key] = $key;
//                }
//            }
//        }
        /*
         * GET ALL PERMISSION
         */
        $permissions = $auth->getPermissions();
        $permissionsArray = [];

        if (!empty($permissions)) {
            foreach ($permissions as $key => $value) {
                $permissionsArray[$key] = $value->description;
            }
            asort($permissionsArray);
        }

        /*
         * GET ROLE "CHILDREN"
         */
//        $roleChildrens = $auth->getChildren($roleParam);
//        if (!empty($roleChildrens)) {
//            $childs = [];
//            foreach ($roleChildrens as $child) {
//                $childs[] = $child->name;
//            }
//            asort($childs);
//            $model->roles = $childs;
//        }

        /*
         * GET ROLE "PERMISSIONS"
         */
        $rolePermissions = $auth->getPermissionsByRole($roleParam);
        if (!empty($rolePermissions)) {
            $permissions = [];
            foreach ($rolePermissions as $permission) {
                $permissions[] = $permission->name;
            }
            $model->permissions = $permissions;
        }

        return $this->render('assign', ['model' => $model, 'permissions' => $permissionsArray]);
    }

    public function actionDelete() {
        $roleParam = \Yii::$app->request->get('role', '');
        if (empty($roleParam)) {
            throw new \yii\web\NotFoundHttpException;
        }

        $auth = \Yii::$app->authManager;
        $role = $auth->getRole($roleParam);
        $hasChilds = $auth->getChildren($roleParam);

        if (!empty($hasChilds)) {
            \Yii::$app->session->setFlash('error', '<strong>Error</strong>, The selected role can not be deleted because it has assigned roles or permissions.');
        } else {
            if ($auth->remove($role)) {
                \Yii::$app->session->setFlash('success', '<strong>Success</strong>, The permission was removed successfully');
            } else {
                \Yii::$app->session->setFlash('error', '<strong>Error</strong>, An error occurred while trying to remove the selected role');
            }
        }

        $this->redirect(\yii\helpers\Url::to(['roles/index']));
    }

}
