<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tbl_company".
 *
 * @property integer $id_company
 * @property string $name
 *
 * @property XmlPublisher[] $xmlPublishers
 */
class CompanyBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_company' => 'Id Company',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXmlPublishers()
    {
        return $this->hasMany(XmlPublisher::className(), ['tbl_company_id_company' => 'id_company']);
    }
}
