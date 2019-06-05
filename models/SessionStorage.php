<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_session".
 *
 * @property integer $id_session
 * @property string $publisher
 * @property string $segment
 * @property integer $loop_count
 * @property string $payload
 */
class SessionStorage extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_session';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['segment', 'payload'], 'string'],
            [['loop_count'], 'integer'],
            [['publisher'], 'string', 'max' => 45],
            [['status'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_session' => 'Id Session',
            'publisher' => 'Publisher',
            'segment' => 'Segment',
            'loop_count' => 'Loop Count',
            'payload' => 'Payload',
        ];
    }

}
