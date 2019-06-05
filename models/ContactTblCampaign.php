<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_contact_tbl_campaign".
 *
 * @property integer $tbl_contact_id_contact
 * @property integer $tbl_campaign_id_campaign
 *
 * @property Contact $tblContactIdContact
 * @property Campaign $tblCampaignIdCampaign
 */
class ContactTblCampaign extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_contact_tbl_campaign';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tbl_contact_id_contact', 'tbl_campaign_id_campaign'], 'required'],
            [['tbl_contact_id_contact', 'tbl_campaign_id_campaign'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tbl_contact_id_contact' => 'Tbl Contact Id Contact',
            'tbl_campaign_id_campaign' => 'Tbl Campaign Id Campaign',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblContactIdContact()
    {
        return $this->hasOne(Contact::className(), ['id_contact' => 'tbl_contact_id_contact']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblCampaignIdCampaign()
    {
        return $this->hasOne(Campaign::className(), ['id_campaign' => 'tbl_campaign_id_campaign']);
    }
}
