<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_xml_layout".
 *
 * @property integer $id_xml_layout
 * @property string $layout
 * @property integer $tbl_xml_publisher_id_publisher
 *
 * @property XmlPublisher $tblXmlPublisherIdPublisher
 */
class XmlLayout extends base\XmlLayoutBase {

}
