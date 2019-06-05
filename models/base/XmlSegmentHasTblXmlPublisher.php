<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tbl_xml_segment_has_tbl_xml_publisher".
 *
 * @property integer $tbl_xml_segment_id_segment
 * @property integer $tbl_xml_publisher_id_publisher
 *
 * @property XmlSegment $tblXmlSegmentIdSegment
 * @property XmlPublisher $tblXmlPublisherIdPublisher
 */
class XmlSegmentHasTblXmlPublisher extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_xml_segment_has_tbl_xml_publisher';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tbl_xml_segment_id_segment', 'tbl_xml_publisher_id_publisher'], 'required'],
            [['tbl_xml_segment_id_segment', 'tbl_xml_publisher_id_publisher'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tbl_xml_segment_id_segment' => 'Tbl Xml Segment Id Segment',
            'tbl_xml_publisher_id_publisher' => 'Tbl Xml Publisher Id Publisher',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblXmlSegmentIdSegment() {
        return $this->hasOne(\app\models\XmlSegment::className(), ['id_segment' => 'tbl_xml_segment_id_segment']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblXmlPublisherIdPublisher() {
        return $this->hasOne(\app\models\XmlPublisher::className(), ['id_publisher' => 'tbl_xml_publisher_id_publisher']);
    }

}
