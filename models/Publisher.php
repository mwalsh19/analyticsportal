<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_publisher".
 *
 * @property integer $id_publisher
 * @property string $name
 * @property string $create_date
 */
class Publisher extends base\PublisherBase {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['create_date'], 'safe'],
            [['name'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_publisher' => 'Id Publisher',
            'name' => 'Name',
            'create_date' => 'Create Date',
        ];
    }

}
