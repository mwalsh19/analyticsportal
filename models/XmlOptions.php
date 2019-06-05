<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_xml_options".
 *
 * @property integer $id_xml_option
 * @property string $root
 * @property string $extra_fields
 * @property integer $tbl_xml_publisher_id_publisher
 *
 * @property XmlPublisher $tblXmlPublisherIdPublisher
 */
class XmlOptions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_xml_options';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['root', 'tbl_xml_publisher_id_publisher'], 'required'],
            [['extra_fields'], 'string'],
            [['tbl_xml_publisher_id_publisher'], 'integer'],
            [['root'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_xml_option' => 'Id Xml Option',
            'root' => 'Root',
            'extra_fields' => 'Extra Fields',
            'tbl_xml_publisher_id_publisher' => 'Tbl Xml Publisher Id Publisher',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblXmlPublisherIdPublisher()
    {
        return $this->hasOne(XmlPublisher::className(), ['id_publisher' => 'tbl_xml_publisher_id_publisher']);
    }
}
