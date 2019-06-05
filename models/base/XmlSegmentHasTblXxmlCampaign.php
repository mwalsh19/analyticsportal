<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tbl_xml_segment_has_tbl_xxml_campaign".
 *
 * @property integer $tbl_xml_segment_id_segment
 * @property integer $tbl_xxml_campaign_id_campaign
 *
 * @property XmlSegment $tblXmlSegmentIdSegment
 * @property XxmlCampaign $tblXxmlCampaignIdCampaign
 */
class XmlSegmentHasTblXxmlCampaign extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_xml_segment_has_tbl_xxml_campaign';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tbl_xml_segment_id_segment', 'tbl_xxml_campaign_id_campaign'], 'required'],
            [['tbl_xml_segment_id_segment', 'tbl_xxml_campaign_id_campaign'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tbl_xml_segment_id_segment' => 'Tbl Xml Segment Id Segment',
            'tbl_xxml_campaign_id_campaign' => 'Tbl Xxml Campaign Id Campaign',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblXmlSegmentIdSegment()
    {
        return $this->hasOne(XmlSegment::className(), ['id_segment' => 'tbl_xml_segment_id_segment']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblXxmlCampaignIdCampaign()
    {
        return $this->hasOne(XxmlCampaign::className(), ['id_campaign' => 'tbl_xxml_campaign_id_campaign']);
    }
}
