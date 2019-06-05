<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tbl_xml_segment".
 *
 * @property integer $id_segment
 * @property string $name
 * @property integer $status
 * @property string $create_date
 *
 * @property Description[] $descriptions
 * @property Session[] $sessions
 * @property Title[] $titles
 * @property Url[] $urls
 * @property XmlSegmentHasTblXmlPublisher[] $xmlSegmentHasTblXmlPublishers
 * @property XmlPublisher[] $tblXmlPublisherIdPublishers
 * @property XmlSegmentHasTblXxmlCampaign[] $xmlSegmentHasTblXxmlCampaigns
 * @property XxmlCampaign[] $tblXxmlCampaignIdCampaigns
 */
class XmlSegmentBase extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_xml_segment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['status'], 'integer'],
            [['create_date'], 'safe'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_segment' => 'Id Segment',
            'name' => 'Name',
            'status' => 'Status',
            'create_date' => 'Create Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDescriptions() {
        return $this->hasMany(\app\models\Description::className(), ['tbl_xml_segment_id_segment' => 'id_segment']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSessions() {
        return $this->hasMany(\app\models\Session::className(), ['tbl_xml_segment_id_segment' => 'id_segment']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTitles() {
        return $this->hasMany(\app\models\Title::className(), ['tbl_xml_segment_id_segment' => 'id_segment']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUrls() {
        return $this->hasMany(\app\models\Url::className(), ['tbl_xml_segment_id_segment' => 'id_segment']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXmlSegmentHasTblXmlPublishers() {
        return $this->hasMany(XmlSegmentHasTblXmlPublisher::className(), ['tbl_xml_segment_id_segment' => 'id_segment']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblXmlPublisherIdPublishers() {
        return $this->hasMany(\app\models\XmlPublisher::className(), ['id_publisher' => 'tbl_xml_publisher_id_publisher'])->viaTable('tbl_xml_segment_has_tbl_xml_publisher', ['tbl_xml_segment_id_segment' => 'id_segment']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXmlSegmentHasTblXxmlCampaigns() {
        return $this->hasMany(XmlSegmentHasTblXxmlCampaign::className(), ['tbl_xml_segment_id_segment' => 'id_segment']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblXxmlCampaignIdCampaigns() {
        return $this->hasMany(\app\models\XmlCampaign::className(), ['id_campaign' => 'tbl_xxml_campaign_id_campaign'])->viaTable('tbl_xml_segment_has_tbl_xxml_campaign', ['tbl_xml_segment_id_segment' => 'id_segment']);
    }

}
