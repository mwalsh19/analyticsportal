<?php

namespace app\models;

use \yii\base\Model;

/**
 *
 * @property string $publisher
 * @property string $segment
 * @property string $loop_count
 */
class ToolForm extends Model {

    public $publisher;
    public $segment;
    public $loop_count;
    public $company;

    public function rules() {
        return [
            [['publisher', 'company'], 'required', 'on' => 'publisher', 'message' => 'Required field'],
            [['segment'], 'safe'],
            [['loop_count'], 'required', 'on' => 'segments', 'message' => 'Required field']
        ];
    }

}
