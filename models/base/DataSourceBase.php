<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "{{%data_source}}".
 *
 * @property integer $id_file
 * @property string $label
 * @property string $file
 * @property integer $owner
 * @property integer $month
 * @property integer $year
 * @property string $code_type
 * @property integer $total_leads
 * @property integer $conversions
 * @property integer $hires
 * @property integer $attending_academy
 * @property integer $id_company_user
 * @property string $create_date
 *
 * @property CompanyUser $idCompanyUser
 */
class DataSourceBase extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%data_source}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['label', 'file', 'owner', 'month', 'year', 'id_company_user'], 'required'],
            [['file'], 'string'],
            [['owner', 'month', 'year', 'total_leads', 'conversions', 'hires', 'attending_academy', 'id_company_user'], 'integer'],
            [['create_date'], 'safe'],
            [['label'], 'string', 'max' => 100],
            [['code_type'], 'string', 'max' => 60],
            [['id_company_user'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\CompanyUser::className(), 'targetAttribute' => ['id_company_user' => 'id_company_user']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_file' => 'Id File',
            'label' => 'Label',
            'file' => 'File',
            'owner' => 'Owner',
            'month' => 'Month',
            'year' => 'Year',
            'code_type' => 'Code Type',
            'total_leads' => 'Total Leads',
            'conversions' => 'Conversions',
            'hires' => 'Hires',
            'attending_academy' => 'Attending Academy',
            'id_company_user' => 'Id Company User',
            'create_date' => 'Create Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCompanyUser() {
        return $this->hasOne(\app\models\CompanyUser::className(), ['id_company_user' => 'id_company_user']);
    }

}
