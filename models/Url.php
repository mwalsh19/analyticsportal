<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_url".
 *
 * @property integer $id_url
 * @property string $segment
 * @property string $url
 * @property integer $tbl_publisher_id_publisher
 *
 * @property ContactPublisher $tblPublisherIdPublisher
 */
class Url extends base\UrlBase {

    public function attributeLabels() {
        return [
            'tbl_xml_publisher_id_publisher' => 'Publisher',
            'tbl_xml_segment_id_segment' => 'Segment',
        ];
    }

}
