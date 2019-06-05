<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\Utils;
use app\models\LoginForm;

class UserController extends \app\components\CustomController {

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
        $users = \app\models\User::find()->all();
        return $this->render('index', ['users' => $users]);
    }

    public function actionCreate() {
        $model = new \app\models\User();
        $auth = Yii::$app->authManager;

        $model->token = Utils::generateRandomString(32);
        $model->password = Utils::generateRandomString(8);

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->email = trim($model->email);
            $model->password = trim($model->password);
            $emailUserExist = \app\models\User::findOne(['email' => $model->email]);
            if (empty($emailUserExist)) {
                $user_email = $model->email;
                $password = $model->password;
                $token = $model->token;

                $model->password = Yii::$app->getSecurity()->generatePasswordHash($password);
                if ($model->save(false)) {

                    \app\components\SendGridEmail::verifyUserAccount($user_email, $password, $token);

                    if (!empty($model->roles)) {
                        $role = $auth->getRole($model->roles);
                        if (!empty($role)) {
                            $auth->assign($role, $model->id_user);
                        }
                    }

                    $this->insertCompaniesOnPivoteTable($model);
                    \Yii::$app->session->setFlash('success', 'The <strong>User</strong> was successfully created.');
                    $this->redirect(['user/index']);
                }
            } else {
                \Yii::$app->session->setFlash('error', 'The email used is already associated with a user.');
            }
        }

        $rolesArray = $auth->getRoles();
        $roles = [];
        if (!empty($rolesArray)) {
            foreach ($rolesArray as $key => $roleObject) {
                $roles[$roleObject->name] = $roleObject->name;
            }
        }

        return $this->render('create', ['model' => $model, 'roles' => $roles]);
    }

    public function actionUpdate() {
        $id = \Yii::$app->request->get('id');
        $auth = Yii::$app->authManager;
        $session = \Yii::$app->session;

        $model = \app\models\User::find()->where('id_user=:id_user', [':id_user' => $id])->with('tblCompanyUserIdCompanyUsers')->one();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->email = trim($model->email);
            if ($model->save()) {
                \app\models\UserHasTblCompanyUser::deleteAll('tbl_user_id_user=:tbl_user_id_user', [
                    ':tbl_user_id_user' => $model->id_user
                ]);

                $this->insertCompaniesOnPivoteTable($model);

                if (!empty($model->companies)) {
                    if (in_array($session['current_company']['id_company_user'], $model->companies) === false) {
                        $logo = \yii\helpers\Url::base() . '/manager/images/beta_logo.png';
                        $session['current_company'] = ['logo' => $logo, 'id_company_user' => 0];
                    }
                }


                $role = $auth->getRole($model->roles);
                if (!empty($role)) {
                    $userHasRoles = $auth->getRolesByUser($model->id_user);
                    foreach ($userHasRoles as $key => $roleObject) {
                        $auth->revoke($roleObject, $model->id_user);
                    }
                    $auth->assign($role, $model->id_user);
                }

                \Yii::$app->session->setFlash('success', 'The <strong>User</strong> was successfully updated. The user will see the changes the next logging');
                $this->redirect(['user/index']);
            }
        }

        if (!empty($model->tblCompanyUserIdCompanyUsers)) {
            $companies = [];

            for ($index = 0; $index < count($model->tblCompanyUserIdCompanyUsers); $index++) {
                $publisher = $model->tblCompanyUserIdCompanyUsers[$index];
                $companies[] = $publisher->id_company_user;
            }

            $model->companies = $companies;
        }

        $rolesArray = $auth->getRoles();
        $roles = [];
        if (!empty($rolesArray)) {
            foreach ($rolesArray as $key => $roleObject) {
                $roles[$roleObject->name] = $roleObject->name;
            }
        }

        if (!$model->hasErrors()) {
            $userRolesArray = $auth->getRolesByUser($model->id_user);
            if (!empty($userRolesArray)) {
                $userRoles = [];
                foreach ($userRolesArray as $key => $roleObject) {
                    $userRoles[$roleObject->name] = $roleObject->name;
                }
                $model->roles = $userRoles;
            }
        }

        return $this->render('update', ['model' => $model, 'roles' => $roles]);
    }

    public function actionDelete() {
        $id = \Yii::$app->request->get('id');

        if ($id != Yii::$app->user->id) {
            $model = \app\models\User::find()->where('id_user=:id_user', [':id_user' => $id])->one();
            if (!empty($model)) {
                \app\models\UserHasTblCompanyUser::deleteAll('tbl_user_id_user=:tbl_user_id_user', [
                    ':tbl_user_id_user' => $model->id_user
                ]);
                $model->delete();
                \Yii::$app->session->setFlash('success', 'The <strong>User</strong> was successfully deleted.');
            }
        } else {
            \Yii::$app->session->setFlash('error', 'The <strong>User</strong> can\'nt be delete, because it is the active user session');
        }

        $this->redirect(['user/index']);
    }

    private function insertCompaniesOnPivoteTable($model) {
        $companiesToInsert = [];
        $command = \Yii::$app->db->createCommand();

        \app\models\UserHasTblCompanyUser::deleteAll('tbl_user_id_user=:tbl_user_id_user', [':tbl_user_id_user' => $model->id_user]);

        if (!empty($model->companies)) {
            foreach ($model->companies as $id_company) {
                $companiesToInsert[] = [
                    'tbl_user_id_user' => $model->id_user,
                    'tbl_company_user_id_company_user' => $id_company
                ];
            }
        }

        if (count($companiesToInsert) > 0) {
            $command->batchInsert('tbl_user_has_tbl_company_user', [
                'tbl_user_id_user', 'tbl_company_user_id_company_user'
                    ], $companiesToInsert)->execute();
        }
    }

    public function actionChangeCompanyLogo() {
        $id = \Yii::$app->request->post('id');

        if (empty($id)) {
            throw new \yii\web\NotFoundHttpException;
        }

        $company = \app\models\CompanyUser::findOne(['id_company_user' => $id]);
        if (!empty($company)) {
            $session = \Yii::$app->session;
            $logo = \yii\helpers\Url::base() . '/manager/images/beta_logo.png';
            if (!empty($company->logo)) {
                $logo = \yii\helpers\Url::base() . '/uploads/company_logo/' . $company->logo;
            }
            $session['current_company'] = ['logo' => $logo, 'id_company_user' => $id];
        }
    }

    public function actionVerifyUserAccount() {
        $this->layout = 'login';
        $token = Yii::$app->request->get('token', '');
        if (empty($token)) {
            throw new \yii\web\NotFoundHttpException;
        }

        $userDbModel = \app\models\User::find()->where('token=:token', [':token' => $token])->one();

        if (empty($userDbModel->token)) {
            return $this->redirect(\yii\helpers\Url::to(['user/invalid-token']));
        }

        return $this->render('verify_msg', ['model' => $userDbModel]);
    }

    public function actionChangePassword() {
        $this->layout = 'login';
        $token = Yii::$app->request->get('token', '');

        if (empty($token)) {
            throw new \yii\web\NotFoundHttpException;
        }

        $userPostModel = new \app\models\User();
        $userPostModel->scenario = \app\models\User::changePasswordScenario;
        $userDbModel = \app\models\User::find()->where('token=:token', [':token' => $token])->one();

        if (empty($userDbModel->token)) {
            return $this->redirect(\yii\helpers\Url::to(['user/invalid-token']));
        } else {
            if ($userPostModel->load(\Yii::$app->request->post()) && $userPostModel->validate()) {
                //
                $security = Yii::$app->getSecurity();
                $userDbModel->password = $security->generatePasswordHash($userPostModel->new_password);
                $userDbModel->token = null;
                if ($userDbModel->save(false)) {
                    return $this->redirect(['manager/index']);
                } else {
                    Yii::$app->session->setFlash('error', 'An error ocurred when try to save your new password');
                }
            }

            return $this->render('change_password', ['model' => $userPostModel]);
        }
    }

    public function actionForgotPassword() {
        $this->layout = 'login';

        $userPostModel = new \app\models\User();
        $userPostModel->scenario = \app\models\User::forgotPasswordSendEmailScenario;

        if ($userPostModel->load(\Yii::$app->request->post()) && $userPostModel->validate()) {
            $userDbModel = \app\models\User::findOne(['email' => trim($userPostModel->email)]);
            //
            if (!empty($userDbModel)) {
                $userDbModel->token = Utils::generateRandomString(32);
                $token = $userDbModel->token;
                $email = $userPostModel->email;

                if ($userDbModel->save()) {
                    \app\components\SendGridEmail::forgotPassword($token, $email);
                    $userPostModel->email = null;
                    Yii::$app->session->setFlash('success', 'We\'ve sent an email with instructions, please check your inbox.');
                } else {
                    Yii::$app->session->setFlash('error', 'An error ocurred when try to reset password');
                }
            } else {
                Yii::$app->session->setFlash('error', 'The entered email is not associated with any user');
            }
        }

        return $this->render('forgot_password_email', ['model' => $userPostModel]);
    }

    public function actionForgotPasswordVerify() {
        $this->layout = 'login';
        $token = Yii::$app->request->get('token', '');
        if (empty($token)) {
            throw new \yii\web\NotFoundHttpException;
        }

        $userDbModel = \app\models\User::find()->where('token=:token', [':token' => $token])->one();
        $userPostModel = new \app\models\User();
        $userPostModel->scenario = \app\models\User::forgotPasswordScenario;

        if (empty($userDbModel->token)) {
            return $this->redirect(\yii\helpers\Url::to(['user/invalid-token']));
        } else {
            if ($userPostModel->load(\Yii::$app->request->post()) && $userPostModel->validate()) {
                $userDbModel->password = Yii::$app->getSecurity()->generatePasswordHash(trim($userPostModel->new_password));
                $userDbModel->token = null;
                if ($userDbModel->save(false)) {
                    return $this->redirect(\yii\helpers\Url::to(['user/forgot-password-status']));
                } else {
                    Yii::$app->session->setFlash('error', 'Oops! an error ocurred when try to save the new password.');
                }
            }
            return $this->render('change_password', ['model' => $userPostModel]);
        }
    }

    public function actionForgotPasswordStatus() {
        $this->layout = 'login';
        return $this->render('forgot_msg');
    }

    public function actionInvalidToken() {
        $this->layout = 'login';
        return $this->render('invalid_token');
    }

    /*
     * LOGIN ACTIONS
     */

    public function actionLogin() {
        $this->layout = 'login';
        $token = Yii::$app->request->get('token', '');

        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(['manager/index']);
        }

        $user = \app\models\User::find()->where('token=:token', [':token' => $token])->one();

        if (!empty($token) && empty($user->token)) {
            return $this->redirect(['user/login']);
        } else {
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                if (!empty($user->token)) {
                    return $this->redirect(['user/change-password', 'token' => $token]);
                } else {
                    return $this->redirect(['manager/index']);
                }
            }
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        $this->redirect(['user/login']);
    }

}
