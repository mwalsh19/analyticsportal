<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tbl_publisher".
 *
 * @property integer $id_publisher
 * @property string $name
 * @property string $create_date
 */
class PublisherBase extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_publisher';
    }

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
