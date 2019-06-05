<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tbl_phone".
 *
 * @property integer $id_phone
 * @property string $phone
 * @property integer $tbl_xml_publisher_id_publisher
 * @property integer $tbl_xml_segment_id_segment
 *
 * @property XmlPublisher $tblXmlPublisherIdPublisher
 * @property XmlSegment $tblXmlSegmentIdSegment
 */
class PhoneBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_phone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', 'tbl_xml_publisher_id_publisher', 'tbl_xml_segment_id_segment'], 'required'],
            [['tbl_xml_publisher_id_publisher', 'tbl_xml_segment_id_segment'], 'integer'],
            [['phone'], 'string', 'max' => 25]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_phone' => 'Id Phone',
            'phone' => 'Phone',
            'tbl_xml_publisher_id_publisher' => 'Tbl Xml Publisher Id Publisher',
            'tbl_xml_segment_id_segment' => 'Tbl Xml Segment Id Segment',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblXmlPublisherIdPublisher()
    {
        return $this->hasOne(XmlPublisher::className(), ['id_publisher' => 'tbl_xml_publisher_id_publisher']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblXmlSegmentIdSegment()
    {
        return $this->hasOne(XmlSegment::className(), ['id_segment' => 'tbl_xml_segment_id_segment']);
    }
}
