<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tbl_title".
 *
 * @property integer $id_title
 * @property string $title
 * @property integer $enabled
 * @property integer $tbl_xml_segment_id_segment
 * @property integer $tbl_xml_publisher_id_publisher
 *
 * @property XmlPublisher $tblXmlPublisherIdPublisher
 * @property XmlSegment $tblXmlSegmentIdSegment
 */
class TitleBase extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_title';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'tbl_xml_segment_id_segment', 'tbl_xml_publisher_id_publisher', 'enabled'], 'required'],
            [['title'], 'string'],
            [['enabled', 'tbl_xml_segment_id_segment', 'tbl_xml_publisher_id_publisher'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_title' => 'Id Title',
            'title' => 'Title',
            'enabled' => 'Enabled',
            'tbl_xml_segment_id_segment' => 'Tbl Xml Segment Id Segment',
            'tbl_xml_publisher_id_publisher' => 'Tbl Xml Publisher Id Publisher',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblXmlPublisherIdPublisher() {
        return $this->hasOne(\app\models\XmlPublisher::className(), ['id_publisher' => 'tbl_xml_publisher_id_publisher']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblXmlSegmentIdSegment() {
        return $this->hasOne(\app\models\XmlSegment::className(), ['id_segment' => 'tbl_xml_segment_id_segment']);
    }

}
