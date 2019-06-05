<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "{{%user_master}}".
 *
 * @property integer $id_user
 * @property string $name
 * @property string $email
 * @property string $password
 * @property integer $status
 * @property string $token
 * @property string $create_date
 *
 * @property UserMasterHasTblCompanyUser[] $userMasterHasTblCompanyUsers
 * @property CompanyUser[] $tblCompanyUserIdCompanyUsers
 */
class UserBase extends \yii\db\ActiveRecord {

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
            [['name', 'email'], 'required'],
            [['status'], 'integer'],
            [['create_date'], 'safe'],
            [['name', 'email'], 'string', 'max' => 45],
            [['password'], 'string', 'max' => 60],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasTblCompanyUsers() {
        return $this->hasMany(\app\models\UserHasTblCompanyUser::className(), ['tbl_user_id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblCompanyUserIdCompanyUsers() {
        return $this->hasMany(\app\models\CompanyUser::className(), ['id_company_user' => 'tbl_company_user_id_company_user'])->viaTable('{{%user_has_tbl_company_user}}', ['tbl_user_id_user' => 'id_user']);
    }

}
