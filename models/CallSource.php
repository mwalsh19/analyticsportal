<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%call_source}}".
 *
 * @property integer $id_call_source
 * @property string $label
 * @property string $file
 * @property string $create_date
 */
class CallSource extends base\CallSourceBase {

    public $sourceFile;
    public $from_hidden_field;
    public $to_hidden_field;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['label', 'sourceFile'], 'required'],
            [['file'], 'string'],
            [['label'], 'string', 'max' => 100],
            [['sourceFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'csv', 'checkExtensionByMimeType' => false],
            [['create_date', 'from_hidden_field', 'to_hidden_field', 'month', 'year', 'sourceFile'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_call_source' => 'Id Call Source',
            'label' => 'Label',
            'file' => 'Call Source',
            'create_date' => 'Create Date',
        ];
    }

    /**
     * @inheritdoc
     */
    public function upload() {
        //call_source_041316_110835.csv format filename
        if ($this->validate()) {
            date_default_timezone_set('America/Los_Angeles');
            $basePath = \Yii::getAlias('@webroot') . '/uploads/call_source';
            //
            $today = date('mdY');
            $current_time = date('Gis');
            //
            //
            $from_array = explode('-', $this->from_hidden_field);
            //
            $fromString = str_replace('-', '', $this->from_hidden_field);
            $toString = str_replace('-', '', $this->to_hidden_field);
            //
            $fileName = "{$fromString}_{$toString}_{$today}_{$current_time}.csv";
            //
            if (!file_exists($basePath)) {
                mkdir($basePath, 0755, true);
            }

            $this->file = $fileName;

            if ($this->sourceFile->saveAs($basePath . '/' . $this->file)) {
                $this->sourceFile = null;
                $this->month = $from_array[0];
                $this->year = $from_array[2];
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function getCallSources() {
        return CallSource::find()->orderBy('create_date DESC')->all();
    }

    public static function getCallSourcesItems() {
        $sources = CallSource::find()->orderBy('create_date DESC')->all();
        $items = [];
        if (!empty($sources)) {
            foreach ($sources as $source) {
                $items[$source->file] = $source->label;
            }
        }
        return $items;
    }

}
