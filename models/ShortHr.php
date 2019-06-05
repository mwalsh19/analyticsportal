<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_short_hr".
 *
 * @property string $job_title
 * @property integer $id_url
 * @property string $real_url
 * @property string $short_url
 * @property string $analytic_url
 */
class ShortHr extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_short_hr';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['real_url'], 'required', 'message' => 'You need to provide at least one final URL'],
            [['job_title', 'short_url', 'analytic_url'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'job_title' => 'Job Title',
            'id_url' => 'Id Url',
            'real_url' => 'Real Url',
            'short_url' => 'Short Url',
            'analytic_url' => 'Analytic Url',
        ];
    }

}
