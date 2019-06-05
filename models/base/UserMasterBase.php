<?php

namespace app\models\base;

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
class UserMasterBase extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'email', 'tbl_company_user_id_company_user'], 'required'],
            [['status', 'tbl_company_user_id_company_user'], 'integer'],
            [['create_date'], 'safe'],
            [['name', 'email'], 'string', 'max' => 45],
            [['password'], 'string', 'max' => 64],
            [['token'], 'string', 'max' => 32]
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
            'tbl_company_user_id_company_user' => 'Tbl Company User Id Company User',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblCompanyUserIdCompanyUser() {
        return $this->hasOne(CompanyUserBase::className(), ['id_company_user' => 'tbl_company_user_id_company_user']);
    }

}
