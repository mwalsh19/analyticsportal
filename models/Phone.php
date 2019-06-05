<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_phone".
 *
 * @property integer $id_phone
 * @property string $phone
 * @property integer $tbl_xml_publisher_id_publisher
 * @property integer $tbl_xml_segment_id_segment
 *
 * @property XmlPublisher $tblXmlPublisherIdPublisher
 * @property XmlSegment $tblXmlSegmentIdSegment
 */
class Phone extends base\PhoneBase {

}
