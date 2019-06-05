<?php

namespace app\models;

use Yii;

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
class UserMaster extends base\UserMasterBase {

    const passwordScenario = 'changePassword';

    /**
     * @inheritdoc
     */
    public $password_repeat;

    public function rules() {
        return [
            [['name', 'email', 'tbl_company_user_id_company_user'], 'required'],
            [['status', 'tbl_company_user_id_company_user'], 'integer'],
            [['create_date'], 'safe'],
            [['name', 'email'], 'string', 'max' => 45],
            [['password', 'token'], 'string', 'max' => 60],
            [['email'], 'email'],
            [['password', 'password_repeat'], 'required', 'on' => self::passwordScenario],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match"],
        ];
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
            'tbl_company_user_id_company_user' => 'Company',
        ];
    }

}
