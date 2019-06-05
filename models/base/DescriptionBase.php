<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tbl_description".
 *
 * @property integer $id_description
 * @property string $description
 * @property integer $enabled
 * @property integer $tbl_xml_segment_id_segment
 * @property integer $tbl_xml_publisher_id_publisher
 *
 * @property XmlPublisher $tblXmlPublisherIdPublisher
 * @property XmlSegment $tblXmlSegmentIdSegment
 */
class DescriptionBase extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_description';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['description', 'tbl_xml_segment_id_segment', 'tbl_xml_publisher_id_publisher', 'enabled'], 'required'],
            [['description'], 'string'],
            [['enabled', 'tbl_xml_segment_id_segment', 'tbl_xml_publisher_id_publisher'], 'integer'],
            [['contain_html'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_description' => 'Id Description',
            'description' => 'Description',
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
