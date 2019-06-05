<?php

namespace app\models;

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
class Campaign extends base\CampaignBase {

    public function attributeLabels() {
        return [
            'tbl_publisher_id_publisher' => 'Publisher',
        ];
    }

}
