<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_xml_segment".
 *
 * @property integer $id_segment
 * @property string $name
 * @property integer $status
 * @property string $xml_template
 * @property string $create_date
 */
class XmlSegment extends base\XmlSegmentBase {

    public $publishers;

    public function rules() {
        return [
            [['name', 'publishers'], 'required'],
            [['status'], 'integer'],
            [['create_date'], 'safe'],
            [['name'], 'string', 'max' => 45]
        ];
    }

}
