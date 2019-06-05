<?php

namespace app\models;

use \yii\base\Model;

/**
 *
 * @property string $campaign_name
 * @property string $attn
 * @property string $io_number
 * @property string $total_net
 * @property string $total_gross
 * @property string $terms
 * @property string $overview
 */
class InsertionOrder extends Model {

    public $campaign_name;
    public $attn;
    public $date;
    public $io_number;
    public $total_net;
    public $total_gross;
    public $terms;
    public $overview;

    public function rules() {
        return [
            [['campaign_name', 'attn', 'date', 'io_number', 'total_net', 'total_gross', 'terms', 'overview'], 'safe']
        ];
    }

}
