<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_xml_campaign".
 *
 * @property integer $id_segment
 * @property string $name
 * @property string $create_date
 * @property integer $tbl_xml_publisher_id_publisher
 *
 * @property XmlPublisher $tblXmlPublisherIdPublisher
 */
class XmlCampaign extends base\XmlCampaignBase {

    public function rules() {
        return [
            [['name', 'status'], 'required']
        ];
    }

    public function attributeLabels() {
        return [
            'tbl_xml_publisher_id_publisher' => 'Publisher',
        ];
    }

}
