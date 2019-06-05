<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id_user
 * @property string $name
 * @property string $email
 * @property string $password
 * @property integer $status
 * @property string $token
 * @property string $create_date
 * @property integer $tbl_company_user_id_company_user
 *
 * @property CompanyUser $tblCompanyUserIdCompanyUser
 */
class User extends base\UserBase implements IdentityInterface {

    const changePasswordScenario = 'changePassword';
    const forgotPasswordScenario = 'forgotPassword';
    const forgotPasswordSendEmailScenario = 'forgotPasswordSendEmail';

    public $current_password;
    public $new_password;
    public $repeat_new_password;
    public $companies;
    public $roles;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'email'], 'required'],
            [['status'], 'integer'],
            [['create_date'], 'safe'],
            [['name', 'email'], 'string', 'max' => 45],
            [['password'], 'string', 'max' => 60],
            [['token'], 'string', 'max' => 32],
            [['companies', 'roles'], 'safe'],
            [['new_password', 'repeat_new_password'], 'required', 'on' => self::changePasswordScenario],
            [['new_password', 'repeat_new_password'], 'required', 'on' => self::forgotPasswordScenario],
            [['email'], 'required', 'on' => self::forgotPasswordSendEmailScenario],
            [['repeat_new_password'], 'compare', 'compareAttribute' => 'new_password', 'message' => 'The new password should be match with repeat password'],
            [['email'], 'email'],
            [['new_password', 'repeat_new_password', 'current_password'], 'safe']
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        if ($this->scenario == self::changePasswordScenario) {
            $scenarios['changePassword'] = ['new_password', 'repeat_new_password'];
        } else if ($this->scenario == self::forgotPasswordScenario) {
            $scenarios['forgotPassword'] = ['new_password', 'repeat_new_password'];
        } else if ($this->scenario == self::forgotPasswordSendEmailScenario) {
            $scenarios['forgotPasswordSendEmail'] = ['email'];
        }
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_user' => 'Id User',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'status' => 'Status',
            'token' => 'Token',
            'create_date' => 'Create Date',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $useremail
     * @return static|null
     */
    public static function findByUserEmail($useremail) {
        return static::find()->where('email=:email', [':email' => $useremail])->with('userHasTblCompanyUsers')->one();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->id_user;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        $result = Yii::$app->getSecurity()->validatePassword($password, $this->password);
        return $result;
    }
}
