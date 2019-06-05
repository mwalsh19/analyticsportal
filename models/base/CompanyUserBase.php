<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "{{%company_user}}".
 *
 * @property integer $id_company_user
 * @property string $name
 * @property string $logo
 * @property string $create_date
 *
 * @property UserHasTblCompanyUser[] $userHasTblCompanyUsers
 * @property User[] $tblUserIdUsers
 */
class CompanyUserBase extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%company_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['create_date'], 'safe'],
            [['name', 'logo'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_company_user' => 'Id Company User',
            'name' => 'Name',
            'logo' => 'Logo',
            'create_date' => 'Create Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasTblCompanyUsers() {
        return $this->hasMany(UserHasTblCompanyUser::className(), ['tbl_company_user_id_company_user' => 'id_company_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUserIdUsers() {
        return $this->hasMany(\app\models\User::className(), ['id_user' => 'tbl_user_id_user'])->viaTable('{{%user_has_tbl_company_user}}', ['tbl_company_user_id_company_user' => 'id_company_user']);
    }

}
