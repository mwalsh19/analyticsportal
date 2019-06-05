<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tbl_xml_layout".
 *
 * @property integer $id_xml_layout
 * @property string $layout
 * @property integer $tbl_xml_publisher_id_publisher
 * @property integer $tbl_xml_segment_id_segment
 *
 * @property XmlSegment $tblXmlSegmentIdSegment
 * @property XmlPublisher $tblXmlPublisherIdPublisher
 */
class XmlLayoutBase extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_xml_layout';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['layout', 'tbl_xml_publisher_id_publisher', 'tbl_xml_segment_id_segment'], 'required'],
            [['layout'], 'string'],
            [['tbl_xml_publisher_id_publisher', 'tbl_xml_segment_id_segment'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_xml_layout' => 'Id Xml Layout',
            'layout' => 'Layout',
            'tbl_xml_publisher_id_publisher' => 'Tbl Xml Publisher Id Publisher',
            'tbl_xml_segment_id_segment' => 'Tbl Xml Segment Id Segment',
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
