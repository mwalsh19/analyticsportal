<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "{{%call_source}}".
 *
 * @property integer $id_call_source
 * @property string $label
 * @property string $file
 * @property integer $month
 * @property integer $year
 * @property string $create_date
 */
class CallSourceBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%call_source}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'file', 'month', 'year'], 'required'],
            [['file'], 'string'],
            [['month', 'year'], 'integer'],
            [['create_date'], 'safe'],
            [['label'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_call_source' => 'Id Call Source',
            'label' => 'Label',
            'file' => 'File',
            'month' => 'Month',
            'year' => 'Year',
            'create_date' => 'Create Date',
        ];
    }
}
