<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tbl_campaign".
 *
 * @property integer $id_campaign
 * @property string $name
 * @property string $create_date
 * @property integer $tbl_publisher_id_publisher
 *
 * @property Publisher $tblPublisherIdPublisher
 * @property ContactTblCampaign[] $contactTblCampaigns
 * @property Contact[] $tblContactIdContacts
 */
class CampaignBase extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_campaign';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'tbl_publisher_id_publisher'], 'required'],
            [['create_date'], 'safe'],
            [['tbl_publisher_id_publisher'], 'integer'],
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
            'create_date' => 'Create Date',
            'tbl_publisher_id_publisher' => 'Tbl Publisher Id Publisher',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblPublisherIdPublisher() {
        return $this->hasOne(\app\models\Publisher::className(), ['id_publisher' => 'tbl_publisher_id_publisher']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactTblCampaigns() {
        return $this->hasMany(\app\models\ContactTblCampaign::className(), ['tbl_campaign_id_campaign' => 'id_campaign']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblContactIdContacts() {
        return $this->hasMany(\app\models\Contact::className(), ['id_contact' => 'tbl_contact_id_contact'])->viaTable('tbl_contact_tbl_campaign', ['tbl_campaign_id_campaign' => 'id_campaign']);
    }

}
