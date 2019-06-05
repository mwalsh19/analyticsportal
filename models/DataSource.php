<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%data_source}}".
 *
 * @property integer $id_file
 * @property string $file
 * @property integer $owner
 * @property integer $month
 * @property integer $year
 * @property string $create_date
 * @property string $sourceFile
 */
class DataSource extends base\DataSourceBase {

    public $sourceFile;
    public $from_hidden_field;
    public $to_hidden_field;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['label', 'owner', 'sourceFile', 'code_type'], 'required'],
            [['owner', 'total_leads', 'conversions', 'hires', 'attending_academy',], 'integer'],
            [['file'], 'string'],
            [['sourceFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'csv', 'checkExtensionByMimeType' => false],
            [['create_date', 'from_hidden_field', 'to_hidden_field', 'month', 'year', 'sourceFile', 'code_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_file' => 'Id File',
            'file' => 'File',
            'owner' => 'Owner',
            'create_date' => 'Create Date',
        ];
    }

    /**
     * @inheritdoc
     */
    public function upload() {
        //040116_043116_041316_110835.csv format filename

        if ($this->validate()) {
            date_default_timezone_set('America/Los_Angeles');

            $basePath = \Yii::getAlias('@webroot') . '/uploads/data_source/data_source_user_' . Yii::$app->user->id;
            //
            $today = date('mdY');
            $current_time = date('Gis');
            //
            $from_array = explode('-', $this->from_hidden_field);
            //
            $fromString = str_replace('-', '', $this->from_hidden_field);
            $toString = str_replace('-', '', $this->to_hidden_field);
            //
            $fileName = "{$fromString}_{$toString}_{$today}_{$current_time}.csv";

            if (!file_exists($basePath)) {
                mkdir($basePath, 0755, true);
            }

            $this->file = $fileName;

            if ($this->sourceFile->saveAs($basePath . '/' . $this->file)) {
                $this->sourceFile = null;
                $this->month = $from_array[0];
                $this->year = $from_array[2];
                // $this->code_type = $code_type;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function getDataSources() {
        $user_session_id = \Yii::$app->user->id;
        $session = \Yii::$app->session;
        $id_company_user = $session['current_company']['id_company_user'];

        $dataSources = \Yii::$app->db->createCommand("select t1.*, t3.name from tbl_data_source t1 join tbl_data_source_share t2 on
        t1.id_file = t2.id_file join tbl_user t3 on t3.id_user = t1.owner where t1.id_company_user = {$id_company_user} AND t2.id_user = {$user_session_id}
        union
        select t1.*, t3.name from tbl_data_source t1 join tbl_user t3 on t3.id_user = t1.owner where t1.id_company_user = {$id_company_user} AND t1.owner = {$user_session_id}")->queryAll();

        return $dataSources;
    }

    public static function getTotals($owner, $file) {
        $obj = new \stdClass();
        $basePath = \Yii::getAlias('@webroot');
        $file = $basePath . "/uploads/data_source/data_source_user_{$owner}/{$file}";

        $total = 0;
        $hired = 0;
        $attendingAcademy = 0;
        if (file_exists($file)) {
            //
            $lines = file($file, FILE_IGNORE_NEW_LINES);
            //
            array_shift($lines); //removes first
            array_pop($lines); //removes last

            $totalRows = count($lines);
            if (count($lines) > 0) {
                for ($i = 0; $i < $totalRows; $i++) {
                    $item = str_getcsv($lines[$i]);
                    $total_col = count($item) - 2;
                    if ($item[0] != 'Grand Totals') {
                        //0,42,32,16,33,34,35,36,38
                        //referrer_code, total, hired, attending_academy, not_qualified, not_interested, no_response, duplicate_app, unqualified_da
                        $total+= (int) $item[$total_col];
                        $hired+= (int) $item[32];
                        $attendingAcademy+= (int) $item[16];
                    }
                }
            }
        }

        $obj->total = (int) $total;
        $obj->hired = (int) $hired;
        $obj->attendingAcademy = (int) $attendingAcademy;
        $obj->conversions = ((int) $hired) + ((int) $attendingAcademy);
        $obj->userSessionId = (int) \Yii::$app->user->id;

        return $obj;
    }

    public static function isCallSource() {
        if (empty(Yii::$app->request->get('type'))) {
            throw new \yii\web\ForbiddenHttpException;
        }
        return Yii::$app->request->get('type') == 'call';
    }

    public static function find() {
        return new DataSourcesQuery(get_called_class());
    }

}

class DataSourcesQuery extends \yii\db\ActiveQuery {

    public function init() {
        $session = \Yii::$app->session;
        $id_company_user = $session['current_company']['id_company_user'];
        if ($id_company_user > 0) {
            $this->andWhere(['id_company_user' => $id_company_user]);
        }
        parent::init();
    }

}
