<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_master_has_tbl_company_user}}".
 *
 * @property integer $tbl_user_master_id_user
 * @property integer $tbl_company_user_id_company_user
 *
 * @property User $tblUserMasterIdUser
 * @property CompanyUser $tblCompanyUserIdCompanyUser
 */
class UserHasTblCompanyUser extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user_has_tbl_company_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tbl_user_id_user', 'tbl_company_user_id_company_user'], 'required'],
            [['tbl_user_id_user', 'tbl_company_user_id_company_user'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tbl_user_id_user' => 'Tbl User Id User',
            'tbl_company_user_id_company_user' => 'Tbl Company User Id Company User',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUserIdUser() {
        return $this->hasOne(User::className(), ['id_user' => 'tbl_user_id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblCompanyUserIdCompanyUser() {
        return $this->hasOne(CompanyUser::className(), ['id_company_user' => 'tbl_company_user_id_company_user']);
    }

}
