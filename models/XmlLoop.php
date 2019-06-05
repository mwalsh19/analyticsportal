<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_xml_loop".
 *
 * @property integer $ref_number
 * @property string $company
 * @property string $description
 * @property string $phone
 * @property string $url
 * @property string $segment
 * @property integer $enabled
 * @property integer $tbl_city_state_id_city_state
 * @property integer $tbl_title_id_title
 *
 * @property CityState $tblCityStateIdCityState
 * @property Title $tblTitleIdTitle
 */
class XmlLoop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_xml_loop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company', 'description', 'phone', 'url', 'segment', 'tbl_city_state_id_city_state', 'tbl_title_id_title'], 'required'],
            [['description', 'url', 'segment'], 'string'],
            [['enabled', 'tbl_city_state_id_city_state', 'tbl_title_id_title'], 'integer'],
            [['company'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 25]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ref_number' => 'Ref Number',
            'company' => 'Company',
            'description' => 'Description',
            'phone' => 'Phone',
            'url' => 'Url',
            'segment' => 'Segment',
            'enabled' => 'Enabled',
            'tbl_city_state_id_city_state' => 'Tbl City State Id City State',
            'tbl_title_id_title' => 'Tbl Title Id Title',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblCityStateIdCityState()
    {
        return $this->hasOne(CityState::className(), ['id_city_state' => 'tbl_city_state_id_city_state']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblTitleIdTitle()
    {
        return $this->hasOne(Title::className(), ['id_title' => 'tbl_title_id_title']);
    }
}
