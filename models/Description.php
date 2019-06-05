<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_description".
 *
 * @property integer $id_description
 * @property string $segment
 * @property string $description
 * @property integer $tbl_publisher_id_publisher
 *
 * @property ContactPublisher $tblPublisherIdPublisher
 */
class Description extends base\DescriptionBase {

    public function attributeLabels() {
        return [
            'tbl_xml_publisher_id_publisher' => 'Publisher',
            'tbl_xml_segment_id_segment' => 'Segment',
            'description' => 'Description (this take HTML)'
        ];
    }

}
