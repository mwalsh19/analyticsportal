<?php

namespace app\controllers;

use \app\components\Utils;

class TenstreetController extends \app\components\CustomController {

    public $layout = 'manager';

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function beforeAction($action) {
        if ($action->id == 'error') {
            $this->layout = false;
        }

        return parent::beforeAction($action);
    }

    public function actionSortFilter() {
        $dataSources = \app\models\DataSource::getDataSources();
        return $this->render('index', ['dataSources' => $dataSources]);
    }

    public function actionGroupedByPublisher() {
        $dataSources = \app\models\DataSource::getDataSources();

        return $this->render('grouped_by_publisher', ['dataSources' => $dataSources]);
    }

    public function actionGroupedByCodeType() {
        $dataSources = \app\models\DataSource::getDataSources();
        $publishers = \app\models\MediaPublisher::find()->select('tenstreet_referrer_part, name')->orderBy('tenstreet_referrer_part ASC')->all();
        $publisherArray = [];
        foreach ($publishers as $object) {
            $publisherArray[$object->tenstreet_referrer_part] = $object->name;
        }
        return $this->render('media_report_by_publisher', ['dataSources' => $dataSources, 'publishers' => $publisherArray]);
    }

    public function actionSortFilterCallSource() {
        return $this->render('call_source_sort_filter');
    }

    public function actionGroupedByPublisherCallSource() {
        return $this->render('call_source_grouped_by_publisher');
    }

    public function actionExecutiveReport() {
        $dataSources = \app\models\DataSource::getDataSources();
        return $this->render('executive_report', ['dataSources' => $dataSources]);
    }

    public function actionGetReport1Data() {
        $this->layout = false;

        $item_selected = \Yii::$app->request->get('item_selected', '');
        if (empty($item_selected)) {
            throw new \yii\web\NotFoundHttpException;
        }

        $publishers = \app\models\MediaPublisher::find()->select('tenstreet_referrer_part')->orderBy('tenstreet_referrer_part ASC')->all();
        $total_publishers = count($publishers);
        $publishers_part = [];
        for ($f = 0; $f < $total_publishers; $f++) {
            $publishers_part[] = $publishers[$f]['tenstreet_referrer_part'];
        }
        $uncategorized_arr = [];
        $groups = [];

        $basePath = \Yii::getAlias('@webroot');
        $item_selected_array = explode('_', $item_selected);
        $id_user = $item_selected_array[0];
        $id_file = $item_selected_array[1];
        $dataSourceFromDb = \app\models\DataSource::findOne(['id_file' => $id_file]);
        $file = $basePath . "/uploads/data_source/data_source_user_{$id_user}/{$dataSourceFromDb->file}";
        $rows_arr = [];

        if (file_exists($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES);
            //array_shift($lines); //removes first
            array_pop($lines); //removes last

            $total_lines = count($lines);

            if ($total_lines > 0) {
                $header_ref = Utils::getColsReference(str_getcsv($lines[0]));

                array_shift($lines);
                $total_lines = count($lines);

                for ($i = 0; $i < $total_lines; $i++) {
                    $item = str_getcsv($lines[$i]);

                    if ($item[$header_ref[Utils::REFERRER_CODE]] != 'Grand Totals') {
                        //0,42,32,16,33,34,35,36,38
                        //referrer_code, total, hired, attending_academy, not_qualified, not_interested, no_response, duplicate_app, unqualified_da
                        if (isset($item[$header_ref[Utils::TOTAL]])) {
                            $rows_arr[] = [
                                'referrer_code' => $item[$header_ref[Utils::REFERRER_CODE]],
                                'total' => $item[$header_ref[Utils::TOTAL]],
                                'hired' => $item[$header_ref[Utils::HIRED]],
                                'attending_academy' => $item[$header_ref[Utils::ATTENDING_ACADEMY]],
                                'not_qualified' => $item[$header_ref[Utils::NOT_QUALIFIED]],
                                'not_interested' => $item[$header_ref[Utils::NOT_INTERESTED]],
                                'no_response' => $item[$header_ref[Utils::NO_RESPONSE]],
                                'duplicate_app' => $item[$header_ref[Utils::DUPLICATE_APP]],
                                'unqualified_da' => $item[$header_ref[Utils::UNQUILIFIED_DA]],
                                'do_not_contact' => $item[$header_ref[Utils::DO_NOT_CONTACT]]];
                        }
                    }
                }

                $rows_arr_sorted = $this->array_msort($rows_arr, array('referrer_code' => SORT_ASC));

                $total_rows_arr_sorted = count($rows_arr_sorted);
                $prev_group_name = '';
                for ($j = 0; $j < $total_rows_arr_sorted; $j++) {
                    $current_row = $rows_arr_sorted[$j];
                    $current_group_name = $this->which_group($current_row['referrer_code'], $publishers_part);

                    if ($current_group_name == 'uncategorized') {
                        $uncategorized_arr[] = $current_row;
                        continue;
                    }

                    if (!isset($groups[$current_group_name])) {
                        $groups[$current_group_name] = [];
                    }

                    $groups[$current_group_name][] = $current_row;

                    $prev_group_name = $current_group_name;
                }
            }
        }
        if (!empty($groups)) {
            ksort($groups);
        }
        return $this->render('grouped_by_publisher_data', ['groups' => $groups, 'uncategorized' => $uncategorized_arr]);
    }

    public function actionGetReport2Data() {
        $this->layout = false;

        $items = \Yii::$app->request->post('items', '');
        $publisher = \Yii::$app->request->post('publisher', '');

        if (empty($items) && empty($publisher)) {
            throw new \yii\web\NotFoundHttpException;
        }

        $group = [];
        $groups = [];
        $months = \app\components\Utils::getMonthArray();
        $basePath = \Yii::getAlias('@webroot');

        $totalItems = count($items);

        for ($index = 0; $index < $totalItems; $index++) {
            $item = $items[$index];
            $isDataSource = $item['type'] == 'datasource';

            if ($isDataSource) {
                $explodeItem = explode('_', $item['source']);
                $id_user = $explodeItem[0];
                $id_file = $explodeItem[1];

                $dataSourceFromDb = \app\models\DataSource::findOne(['id_file' => $id_file]);
                $file = $basePath . "/uploads/data_source/data_source_user_{$id_user}/{$dataSourceFromDb->file}";

                if (file_exists($file)) {
                    $groups[] = [
                        'items' => $this->parseCSV($file, $publisher, $group),
                        'code_type' => $dataSourceFromDb->code_type,
                        'date' => $dataSourceFromDb->year . ', ' . $months[$dataSourceFromDb->month],
                        'isDataSource' => true
                    ];
                }
            }

            if (!$isDataSource) {
                $file = $basePath . "/uploads/call_source/{$item['source']}";

                if (file_exists($file)) {
                    $groups[] = [
                        'items' => $this->parseCSV($file, $publisher, $group, false),
                        'code_type' => 'call',
                        'date' => '',
                        'isDataSource' => false
                    ];
                }
            }
        }

        return $this->render('media_report_data', [
                    'groups' => $groups,
        ]);
    }

    public function actionGetReport3Data() {
        $this->layout = false;

        $item_selected = \Yii::$app->request->get('item_selected', '');
        if (empty($item_selected)) {
            throw new \yii\web\NotFoundHttpException;
        }

        $publishers = \app\models\MediaPublisher::find()->select('tenstreet_referrer_part')->orderBy('tenstreet_referrer_part ASC')->all();
        $total_publishers = count($publishers);

        $publishers_part = [];
        for ($f = 0; $f < $total_publishers; $f++) {
            $publishers_part[] = $publishers[$f]['tenstreet_referrer_part'];
        }
        $uncategorized_arr = [];
        $groups = [];

        $basePath = \Yii::getAlias('@webroot');
        $file = $basePath . "/uploads/call_source/{$item_selected}";
        $rows_arr = [];

        if (file_exists($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES);
            $total_lines = count($lines);
            if ($total_lines > 0) {
                for ($i = 0; $i < $total_lines; $i++) {
                    $item = str_getcsv($lines[$i]);
                    $rows_arr[] = [
                        'referrer_code' => $item[0],
                        'tracking_number' => $item[1],
                        'calls' => $item[2]
                    ];
                }

                $rows_arr_sorted = $this->array_msort($rows_arr, array('referrer_code' => SORT_ASC));
                $total_rows_arr_sorted = count($rows_arr_sorted);

                $prev_group_name = '';
                for ($j = 0; $j < $total_rows_arr_sorted; $j++) {
                    $current_row = $rows_arr_sorted[$j];
                    $current_group_name = $this->which_group($current_row['referrer_code'], $publishers_part);

                    if ($current_group_name == 'uncategorized') {
                        $uncategorized_arr[] = $current_row;
                        continue;
                    }

                    if (!isset($groups[$current_group_name])) {
                        $groups[$current_group_name] = [];
                    }

                    $groups[$current_group_name][] = $current_row;

                    $prev_group_name = $current_group_name;
                }
            }
        }
        if (!empty($groups)) {
            ksort($groups);
        }
        return $this->render('grouped_by_publisher_data_2', ['groups' => $groups, 'uncategorized' => $uncategorized_arr]);
    }

    public function actionGetReport4Data() {
        $this->layout = false;
        $items = \Yii::$app->request->post('items', '');

        if (empty($items)) {
            throw new \yii\web\NotFoundHttpException;
        }

        $group = [];
        $groups = [];
        $months = \app\components\Utils::getShortMonthArray();
        $basePath = \Yii::getAlias('@webroot');

        $totalItems = count($items);

        for ($index = 0; $index < $totalItems; $index++) {
            $item = $items[$index];
            $isDataSource = $item['type'] == 'R' || $item['type'] == 'S';
            $isMDT = $item['mtd'] == 1 || $item['mtd'] == '1';

            if ($isDataSource) {
                $explodeItem = explode('_', $item['source']);
                $id_user = $explodeItem[0];
                $id_file = $explodeItem[1];

                $data = \app\models\DataSource::findOne(['id_file' => $id_file]);
                $file = $basePath . "/uploads/data_source/data_source_user_{$id_user}/{$data->file}";

                if (file_exists($file)) {
                    $groups[] = [
                        'label' => $data->label,
                        'items' => $this->parseCSV($file, '', $group, true, true),
                        'code_type' => $item['type'],
                        'dateRange' => $this->getLabelField($isMDT, $data, $months),
                        'isDataSource' => true,
                        'isMDT' => $isMDT
                    ];
                }
            }

            if (!$isDataSource) {
                $data = \app\models\CallSource::find()->where(['file' => $item['source']])->one();
                $file = $basePath . "/uploads/call_source/{$item['source']}";

                if (file_exists($file)) {
                    $groups[] = [
                        'label' => $data->label,
                        'items' => $this->parseCSV($file, '', $group, false, true),
                        'code_type' => $item['type'],
                        'dateRange' => $this->getLabelField($isMDT, $data, $months),
                        'isDataSource' => false,
                        'isMDT' => $isMDT
                    ];
                }
            }
        }

        return $this->render('media_report_data2', [
                    'groups' => $groups,
        ]);
    }

    private function getLabelField($isMDT = false, $data = null, $months = null) {
        $label = '';
        if ($isMDT) {
            $label = $months[$data->month] . ' ' . $data->year . ' MTD';
        } else {
            $a_date = $data->year . '-' . $data->month . '-01';
            $lastDayOfMonth = date('t', strtotime($a_date));
            $label = $months[$data->month] . ', 1 - ' . $lastDayOfMonth . ', ' . $data->year;
        }
        return $label;
    }

    private function parseCSV($file = '', $publisher = '', $group = [], $isDataSource = true, $uncategorized = false) {
        $publishers_part[] = $publisher;
        $lines = file($file, FILE_IGNORE_NEW_LINES);

        if ($isDataSource) {
            array_pop($lines); //removes last
        }
        //
        $total_lines = count($lines);
        $rows_arr = [];

        if ($total_lines > 0) {

            if ($isDataSource) {
                $header_ref = Utils::getColsReference(str_getcsv($lines[0]));
                array_shift($lines);
                $total_lines = count($lines);
            }

            for ($i = 0; $i < $total_lines; $i++) {
                $item = str_getcsv($lines[$i]);

                if ($isDataSource) {
                    //$cols_ref = Utils::getColsReference($lines[0]);
                    //$total_col = count($item) - 2;
                    if ($item[$header_ref[Utils::REFERRER_CODE]] != 'Grand Totals') {
                        //0,42,32,16,33,34,35,36,38
                        //referrer_code, total, hired, attending_academy, not_qualified, not_interested, no_response, duplicate_app, unqualified_da
                        if (isset($item[$header_ref[Utils::TOTAL]])) {
                            $rows_arr[] = [
                                'referrer_code' => $item[$header_ref[Utils::REFERRER_CODE]],
                                'total' => $item[$header_ref[Utils::TOTAL]],
                                'hired' => $item[$header_ref[Utils::HIRED]],
                                'attending_academy' => $item[$header_ref[Utils::ATTENDING_ACADEMY]],
                                'not_qualified' => $item[$header_ref[Utils::NOT_QUALIFIED]],
                                'not_interested' => $item[$header_ref[Utils::NOT_INTERESTED]],
                                'no_response' => $item[$header_ref[Utils::NO_RESPONSE]],
                                'duplicate_app' => $item[$header_ref[Utils::DUPLICATE_APP]],
                                'unqualified_da' => $item[$header_ref[Utils::UNQUILIFIED_DA]],
                                'do_not_contact' => $item[$header_ref[Utils::DO_NOT_CONTACT]]
                            ];
                        }
                    }
                } else {
                    $rows_arr[] = [
                        'referrer_code' => $item[0],
                        'tracking_number' => $item[1],
                        'calls' => $item[2]
                    ];
                }
            }

            $rows_arr_sorted = $this->array_msort($rows_arr, array('referrer_code' => SORT_ASC));

            $total_rows_arr_sorted = count($rows_arr_sorted);
            $prev_group_name = '';

            for ($j = 0; $j < $total_rows_arr_sorted; $j++) {
                $current_row = $rows_arr_sorted[$j];
                $current_group_name = $this->which_group($current_row['referrer_code'], $publishers_part);

                if ($current_group_name == 'uncategorized' && !$uncategorized) {
                    continue;
                }

                if (!isset($group[$current_group_name])) {
                    $group[$current_group_name] = [];
                }

                $group[$current_group_name][] = $current_row;
                $prev_group_name = $current_group_name;
            }
        }
        return $group;
    }

    public function actionGetGrandTotal() {
        $item_selected = \Yii::$app->request->get('item_selected', '');
        if (empty($item_selected)) {
            throw new \yii\web\NotFoundHttpException;
        }
        $basePath = \Yii::getAlias('@webroot');
        $item_selected_array = explode('_', $item_selected);
        //
        $id_user = $item_selected_array[0];
        $id_file = $item_selected_array[1];
        //
        $dataSourceFromDb = \app\models\DataSource::findOne(['id_file' => $id_file]);
        //
        $file = $basePath . "/uploads/data_source/data_source_user_{$id_user}/{$dataSourceFromDb->file}";

        $totals = 0;
        if (file_exists($file)) {
            //
            $lines = file($file, FILE_IGNORE_NEW_LINES);
            //
            //array_shift($lines); //removes first
            array_pop($lines); //removes last
            $total_lines = count($lines);

            if ($total_lines > 0) {
                $header_ref = Utils::getColsReference(str_getcsv($lines[0]));

                array_shift($lines);
                $total_lines = count($lines);

                for ($i = 0; $i < $total_lines; $i++) {
                    $item = str_getcsv($lines[$i]);
                    // $total_col = count($item) - 2;

                    if ($item[$header_ref[Utils::REFERRER_CODE]] != 'Grand Totals') {
                        //0,42,32,16,33,34,35,36,38, 39
                        //referrer_code, total, hired, attending_academy, not_qualified, not_interested, no_response, duplicate_app, unqualified_da, do_not_contact
                        $totals += (int) $item[$header_ref[Utils::TOTAL]];
                    }
                }
            }
        }

        header('Content-Type: application/json');
        $obj = new \stdClass();
        $obj->total = $totals;
        echo json_encode($obj);
    }

    public function actionGetDataSource() {
        $item_selected = \Yii::$app->request->get('item_selected', '');
        if (empty($item_selected)) {
            throw new \yii\web\NotFoundHttpException;
        }

        $basePath = \Yii::getAlias('@webroot');
        $item_selected_array = explode('_', $item_selected);
        //
        $id_user = $item_selected_array[0];
        $id_file = $item_selected_array[1];
        //
        $dataSourceFromDb = \app\models\DataSource::findOne(['id_file' => $id_file]);
        //
        $file = $basePath . "/uploads/data_source/data_source_user_{$id_user}/{$dataSourceFromDb->file}";
        //
        $total = 0;
        $rows_arr = [];

        if (file_exists($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES);

            //array_shift($lines); //removes first
            array_pop($lines); //removes last

            $total_lines = count($lines);

            if ($total_lines > 0) {

                $header_ref = Utils::getColsReference(str_getcsv($lines[0]));

                array_shift($lines);
                $total_lines = count($lines);

                for ($i = 0; $i < $total_lines; $i++) {
                    $item = str_getcsv($lines[$i]);

                    if ($item[$header_ref[Utils::REFERRER_CODE]] != 'Grand Totals') {
                        //0,42,32,16,33,34,35,36,38, 39
                        //referrer_code, total, hired, attending_academy, not_qualified, not_interested, no_response, duplicate_app, unqualified_da, do_not_contact
                        if (isset($item[$header_ref[Utils::TOTAL]])) {
                            $rows_arr[] = [
                                $item[$header_ref[Utils::REFERRER_CODE]],
                                $item[$header_ref[Utils::TOTAL]],
                                $item[$header_ref[Utils::HIRED]],
                                $item[$header_ref[Utils::ATTENDING_ACADEMY]],
                                $item[$header_ref[Utils::NOT_QUALIFIED]],
                                $item[$header_ref[Utils::NOT_INTERESTED]],
                                $item[$header_ref[Utils::NO_RESPONSE]],
                                $item[$header_ref[Utils::DUPLICATE_APP]],
                                $item[$header_ref[Utils::UNQUILIFIED_DA]],
                                $item[$header_ref[Utils::DO_NOT_CONTACT]]
                            ];
                        }
                    }
                }
            }
        }

        header('Content-Type: application/json');
        $obj = new \stdClass();
        $obj->iTotalRecord = $total;
        $obj->aaData = $rows_arr;
        echo json_encode($obj);
    }

    public function actionGetCallSource() {
        $item_selected = \Yii::$app->request->get('item_selected', '');
        if (empty($item_selected)) {
            throw new \yii\web\NotFoundHttpException;
        }

        $basePath = \Yii::getAlias('@webroot');
        $file = $basePath . "/uploads/call_source/{$item_selected}";
        //
        $total = 0;
        $rows_arr = [];

        if (file_exists($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES);
            $total_lines = count($lines);
            if ($total_lines > 0) {
                for ($i = 0; $i < $total_lines; $i++) {
                    $item = str_getcsv($lines[$i]);
                    $rows_arr[] = [
                        $item[0],
                        $item[1],
                        $item[2],
                    ];
                }
            }
        }

        header('Content-Type: application/json');
        $obj = new \stdClass();
        $obj->iTotalRecord = $total;
        $obj->aaData = $rows_arr;
        echo json_encode($obj);
    }

    public function actionTenstreetImport() {
        $month = \Yii::$app->request->get('month');
        $year = \Yii::$app->request->get('year');
        $tenstreet_files = [];

        if (!empty($month) && !empty($year)) {
            $tenstreet_files = \app\models\TenstreetPost::find()->where('YEAR(FROM_UNIXTIME(create_date)) = :year AND MONTH(FROM_UNIXTIME(create_date)) = :month', [
                        ':month' => $month,
                        ':year' => $year
                    ])->orderBy('create_date DESC')->all();
        }
        return $this->render('tenstreet_import', ['tenstreet_files' => $tenstreet_files]);
    }

    public function actionImportFile() {
        date_default_timezone_set('America/Los_Angeles');

        $id_file = \Yii::$app->request->post('id_file', '');
        $file = \Yii::$app->request->post('file', '');
        $label = \Yii::$app->request->post('label', '');
        if (empty($id_file) && empty($file) && empty($label)) {
            throw new \yii\web\NotFoundHttpException;
        }
        $model = \app\models\TenstreetPost::findOne(['id_file' => $id_file]);
        if (!empty($model)) {
            $fileFromTenstreet = file_get_contents($file);

            $user_session_id = \Yii::$app->user->id;
            $today = date('mdY');
            $current_time = date('Gis');
            $fromString = date('m', $model->create_date) . '01' . date('Y', $model->create_date);
            $toString = date('mdY', $model->create_date);
            $fileName = "{$fromString}_{$toString}_{$today}_{$current_time}.csv";

            $basePath = \Yii::getAlias('@webroot') . '/uploads/data_source/data_source_user_' . $user_session_id;

            if (!file_exists($basePath)) {
                mkdir($basePath, 0755, true);
            }
            $code_type = '';
            if (strpos($model->file, "referrer") !== false) {
                $code_type = 'referrer';
            } else {
                $code_type = 'source';
            }
            $dataSourceModel = new \app\models\DataSource();
            $dataSourceModel->label = $label;
            $dataSourceModel->file = $fileName;
            $dataSourceModel->owner = $user_session_id;
            $dataSourceModel->month = date('m', $model->create_date);
            $dataSourceModel->year = date('Y', $model->create_date);
            $dataSourceModel->code_type = $code_type;
            $dataSourceModel->id_company_user = $model->id_company_user;

            if (file_put_contents($basePath . '/' . $fileName, $fileFromTenstreet) !== false) {
                $totals = \app\models\DataSource::getTotals($user_session_id, $fileName);

                if (!empty($totals)) {
                    $dataSourceModel->total_leads = $totals->total;
                    $dataSourceModel->hires = $totals->hired;
                    $dataSourceModel->attending_academy = $totals->attendingAcademy;
                    $dataSourceModel->conversions = $totals->conversions;
                }
                if ($dataSourceModel->save(false)) {
                    exit();
                } else {
                    @unlink($basePath . '/' . $fileName);
                    throw new \yii\web\ServerErrorHttpException;
                }
            } else {
                @unlink($basePath . '/' . $fileName);
                throw new \yii\web\ServerErrorHttpException;
            }
        } else {
            throw new \yii\web\ServerErrorHttpException;
        }
    }

    private function array_msort($array, $cols) {
        $colarr = array();
        foreach ($cols as $col => $order) {
            $colarr[$col] = array();
            foreach ($array as $k => $row) {
                $colarr[$col]['_' . $k] = strtolower($row[$col]);
            }
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\'' . $col . '\'],' . $order . ',';
        } $eval = substr($eval, 0, -1) . ');';
        eval($eval);
        $ret = array();

        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k, 1);
                if (!isset($ret[$k]))
                    $ret[$k] = $array[$k];
                $ret[$k][$col] = $array[$k][$col];
            }
        }

        return $ret;
    }

    private function which_group($needle, array $haystack) {
        foreach ($haystack as $item) {
            $parts = explode(',', $item);

            for ($index = 0; $index < count($parts); $index++) {
                if (false !== stripos($needle, trim($parts[$index]))) {
                    return $item;
                }
            }
        }

        return 'uncategorized';
    }

}
