<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_title".
 *
 * @property integer $id_title
 * @property string $segment
 * @property string $publisher
 * @property string $title
 * @property integer $enabled
 * @property integer $tbl_publisher_id_publisher
 *
 * @property ContactPublisher $tblPublisherIdPublisher
 */
class Title extends base\TitleBase {

    public function attributeLabels() {
        return [
            'tbl_xml_publisher_id_publisher' => 'Publisher',
            'tbl_xml_segment_id_segment' => 'Segment',
        ];
    }

}
