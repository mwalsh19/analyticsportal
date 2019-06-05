<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tenstreet_post}}".
 *
 * @property integer $id_file
 * @property string $file
 * @property integer $create_date
 * @property integer $id_company_user
 */
class TenstreetPost extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%tenstreet_post}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['file', 'create_date'], 'required'],
            [['file'], 'string'],
            [['create_date', 'id_company_user'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_file' => 'Id File',
            'file' => 'File',
            'create_date' => 'Create Date',
            'id_company_user' => 'Id Company User',
        ];
    }

    public function getCompanyInfo() {
        return $this->hasOne(CompanyUser::className(), ['id_company_user' => 'id_company_user']);
    }

}
