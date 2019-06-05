<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tbl_xxml_campaign".
 *
 * @property integer $id_campaign
 * @property string $name
 * @property integer $status
 * @property string $create_date
 *
 * @property XmlSegmentHasTblXxmlCampaign[] $xmlSegmentHasTblXxmlCampaigns
 * @property XmlSegment[] $tblXmlSegmentIdSegments
 */
class XmlCampaignBase extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_xxml_campaign';
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
            'id_campaign' => 'Id Campaign',
            'name' => 'Name',
            'status' => 'Status',
            'create_date' => 'Create Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXmlSegmentHasTblXxmlCampaigns() {
        return $this->hasMany(XmlSegmentHasTblXxmlCampaign::className(), ['tbl_xxml_campaign_id_campaign' => 'id_campaign']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblXmlSegmentIdSegments() {
        return $this->hasMany(\app\models\XmlSegment::className(), ['id_segment' => 'tbl_xml_segment_id_segment'])->viaTable('tbl_xml_segment_has_tbl_xxml_campaign', ['tbl_xxml_campaign_id_campaign' => 'id_campaign']);
    }

}
