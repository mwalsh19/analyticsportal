<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_city_state".
 *
 * @property integer $id_city_state
 * @property string $state
 * @property string $city
 * @property integer $enabled
 *
 * @property XmlLoop[] $xmlLoops
 */
class CityState extends base\CityStateBase {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['state', 'city'], 'required'],
            [['enabled'], 'integer'],
            [['state', 'city'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_city_state' => 'Id City State',
            'state' => 'State',
            'city' => 'City',
            'enabled' => 'Enabled',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXmlLoops() {
        return $this->hasMany(XmlLoop::className(), ['tbl_city_state_id_city_state' => 'id_city_state']);
    }

}
