<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%data_source_share}}".
 *
 * @property integer $id_share
 * @property integer $id_file
 * @property integer $id_user
 */
class DataSourceShare extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%data_source_share}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_file', 'id_user'], 'required'],
            [['id_file', 'id_user'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_share' => 'Id Share',
            'id_file' => 'Id File',
            'id_user' => 'Id User',
        ];
    }
}
