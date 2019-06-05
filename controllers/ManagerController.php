<?php

namespace app\controllers;

use Yii;

//use yii\filters\AccessControl;
//use yii\filters\VerbFilter;

class ManagerController extends \app\components\CustomController {

    public $layout = 'manager';
    private $segments = [
        'D' => 'Dedicated Main',
        'DC' => 'Dedicated Cabela',
        'E' => 'Experienced',
        'RG' => 'Recent Grads',
        'S' => 'Students'
    ];
    private $segmentListAbr = [
        'D',
        'DC',
        'E',
        'RG',
        'S'
    ];

//    private $actions = [
//        'index',
//        'xml-automation',
//        'xml-automation-review',
//        'xml-automation-url',
//        'xml-automation-history',
//        'xml-automation-segments',
//        'remove-file',
//        'shorturl',
//        'analyticsshorturl',
//        'download-file',
//        'get-campaigns',
//        'get-publishers',
//        'get-segments',
//        'get-segment-relations',
//        'tool'
//    ];
//
//    public function behaviors() {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => $this->actions,
//                'rules' => [
//                    [
//                        'actions' => $this->actions,
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                    [
//                        'allow' => true,
//                        'actions' => ['login', 'signup'],
//                        'roles' => ['?'],
//                    ],
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                // 'logout' => ['post'],
//                ],
//            ],
//        ];
//    }

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

    public function actionIndex() {

        return $this->render('index');
    }

    public function actionXmlAutomationReview() {

        return $this->render('xml_automation/review');
    }

    public function actionGetPublishers() {
        $id = \Yii::$app->request->get('id_company');
        $options = '';
        if (!empty($id)) {
            $publishers = \app\models\XmlPublisher::find()
                            ->where('tbl_company_id_company=:id_company', [':id_company' => $id])
                            ->orderBy('name ASC')->all();

            if (!empty($publishers)) {
                $options.= '<option selected>SELECT PUBLISHER</option>';
                for ($index = 0; $index < count($publishers); $index++) {
                    $publisherObject = $publishers[$index];
                    $options.= "<option value='{$publisherObject->id_publisher}'>{$publisherObject->name}</option>";
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['publishers' => $options]);
    }

    public function actionGetCampaigns() {
        $id = \Yii::$app->request->get('id_publisher');
        $options = '';
        if (!empty($id)) {
            $campaigns = \app\models\XmlCampaign::find()
                    ->where('tbl_xml_publisher_id_publisher=:id_publisher AND status=1', [':id_publisher' => $id])
                    ->orderBy('name ASC')
                    ->all();

            if (!empty($campaigns)) {
                $options.= '<option selected>SELECT SEGMENT</option>';
                for ($index = 0; $index < count($campaigns); $index++) {
                    $campaignObject = $campaigns[$index];
                    $options.= "<option value='{$campaignObject->id_campaign}'>{$campaignObject->name}</option>";
                }
            }
        }

        $obj = [
            'campaigns' => $options
        ];

        header('Content-Type: application/json');
        echo json_encode($obj);
    }

    public function actionGetSegmentRelations() {
        $id = \Yii::$app->request->get('id_campaign');
        $titles = '';
        $descriptions = '';
        $urls = '';
        if (!empty($id)) {
            $cammpaignRelations = \app\models\XmlCampaign::find()
                    ->with('titles', 'descriptions', 'urls')
                    ->where('id_campaign=:id_campaign', [':id_campaign' => $id])
                    ->all();

            if (!empty($cammpaignRelations)) {
                $campaignArray = $cammpaignRelations[0];
                if (!empty($campaignArray->titles)) {
                    $titlesFromDB = $campaignArray->titles;
                    for ($index = 0; $index < count($titlesFromDB); $index++) {
                        $titleObject = $titlesFromDB[$index];
                        $titles.= "<p>{$titleObject->title}</p>";
                    }
                }
                if (!empty($campaignArray->descriptions)) {
                    $descriptionsFromDB = $campaignArray->descriptions;
                    for ($index = 0; $index < count($descriptionsFromDB); $index++) {
                        $descriptionObject = $descriptionsFromDB[$index];
                        $descriptions.= "<p>{$descriptionObject->description}</p>";
                    }
                }
                if (!empty($campaignArray->urls)) {
                    $urlsFromDB = $campaignArray->urls;
                    for ($index = 0; $index < count($urlsFromDB); $index++) {
                        $urlnObject = $urlsFromDB[$index];
                        $urls.= "<p>{$urlnObject->url}</p>";
                    }
                }
            }
        }

        $obj = [
            'titles' => strlen($titles) > 0 ? $titles : 'No results found',
            'descriptions' => strlen($descriptions) > 0 ? $descriptions : 'No results found',
            'urls' => strlen($urls) > 0 ? $urls : 'No results found'
        ];

        header('Content-Type: application/json');
        echo json_encode($obj);
    }

    public function actionXmlAutomation() {
        $request = \Yii::$app->request;
        $session = \Yii::$app->session;

        $model = new \app\models\ToolForm(['scenario' => 'publisher']);

        if ($request->isPost) {
            $model->load($request->post(), 'ToolForm');

            if ($model->validate()) {
                $session->set('publisher', $model->publisher);
                $session->set('currentSegmentIndex', 0);
                $this->redirect(['manager/xml-automation-segments']);
            }
        } else {
            $session->set('currentSegmentIndex', 0);
        }

        return $this->render('xml_automation/index', [
                    'model' => $model]);
    }

    public function actionXmlAutomationSegments() {
        $request = \Yii::$app->request;
        $session = \Yii::$app->session;

        $prev_segment = $request->get('segment', '');

        $segmentPos = $session->get('currentSegmentIndex', 0);

        if (!empty($prev_segment)) {
            $segmentPos--;
            $session->set('currentSegmentIndex', $segmentPos);
        }

        $publisher = $session->get('publisher', '');
        $segmentAbr = $this->segmentListAbr[$segmentPos];

        $sessionStorage = \app\models\SessionStorage::find()->where('publisher=:publisher AND segment=:segment', [
                    ':publisher' => $publisher,
                    ':segment' => $segmentAbr
                ])->one();

        $city_state_from_db = \app\models\CityState::find()->all();

        $tool_model = new \app\models\ToolForm(['scenario' => 'segments']);

        if (empty($sessionStorage)) {
            $sessionStorage = new \app\models\SessionStorage();
        } else {
            $tool_model->loop_count = $sessionStorage->loop_count;
        }

        if ($request->isPost) {
            $cityStatesArray = $request->post('city_state', []);
            $loop_count = new \app\models\ToolForm(['scenario' => 'segments']);
            $loop_count->load($request->post(), 'ToolForm');

            if (count($cityStatesArray) && $loop_count->validate()) {
                if (empty($sessionStorage)) {
                    $sessionStorage = new \app\models\SessionStorage();
                }

                $sessionStorage->publisher = $publisher;
                $sessionStorage->segment = $segmentAbr;
                $sessionStorage->loop_count = $loop_count->loop_count;
                $sessionStorage->payload = serialize($cityStatesArray);
                $sessionStorage->save(false);

                $segmentPos++;

                if ($segmentPos == count($this->segmentListAbr)) {
                    $session->remove('currentSegmentIndex');

                    $this->xmlJobs();
                } else {
                    $session->set('currentSegmentIndex', $segmentPos);
                    $segmentAbr = $this->segmentListAbr[$segmentPos];

                    $this->redirect(['manager/xml-automation-segments']);
                }
            } else {
                //set a flash ans show alert
                $session->setFlash('errorMsg', 'Please select at least one state or city');
            }
        }

        return $this->render('xml_automation/segments', [
                    'tool_model' => $tool_model,
                    'cityStateList' => $city_state_from_db,
                    'sessionStorage' => $sessionStorage,
                    'segment' => $this->segments[$segmentAbr],
                    'segmentPos' => $segmentPos]);
    }

    private function xmlJobs() {
        $publisher = \Yii::$app->session->get('publisher', '');
        $sessionRecords = \app\models\SessionStorage::find()
                ->where('publisher=:publisher', [':publisher' => $publisher])
                ->all();

        if (!empty($sessionRecords)) {
            date_default_timezone_set('GMT');
            $date = date('D, d M Y h:i:s e', strtotime('now'));
            $file_date = date('d_m_Y_h_i_s', strtotime('now'));

            $xml = '';
            $xml.= '<?xml version="1.0" encoding="utf-8"?>
                            <source>
                                <publisher>Swift Transportation</publisher>
                                <publisherurl>http://swifttrans.com</publisherurl>';

            for ($index = 0; $index < count($sessionRecords); $index++) {
                $record = $sessionRecords[$index];
                $payloadString = $record->payload;
                $cityStateArray = !empty($payloadString) ? unserialize($payloadString) : '';
                $segment = $record->segment;
                $loopcount = $record->loop_count;

                $total_city_states = count($cityStateArray);
                $total_jobs = $total_city_states * $loopcount;


                $title_array = \app\models\Title::find()->where(
                                'publisher=:publisher AND segment=:segment', [
                            ":publisher" => strtolower($publisher),
                            ":segment" => $segment
                                ]
                        )->all();

                $url_array = \app\models\Url::find()->where(
                                'publisher=:publisher AND segment=:segment', [
                            ":publisher" => strtolower($publisher),
                            ":segment" => $segment
                                ]
                        )->all();


                $description_array = \app\models\Description::find()->where(
                                'publisher=:publisher AND segment=:segment', [
                            ":publisher" => strtolower($publisher),
                            ":segment" => $segment
                                ]
                        )->all();


                $counter1 = 1;
                if (!empty($title_array) && !empty($url_array) && !empty($description_array)) {
                    $total_titles = count($title_array);
                    $total_urls = count($url_array);
                    $total_descriptions = count($description_array);

                    $counter2 = 0;
                    $counter3 = 0;
                    $counter4 = 0;
                    $counter5 = 0;

                    for ($index2 = 0; $index2 < $total_jobs; $index2++) {
                        $final_url = '';
                        $city = '';
                        $state = '';

                        $title = $title_array[$counter2]->title;
                        $segmentUrl = $url_array[$counter3]->url;
                        $description = $description_array[$counter4]->description;
                        $slices = explode('_', $cityStateArray[$counter5]);

                        /* SEARCH STATE AND CITY */
                        if (count($slices) == 2) {
                            $state = trim($slices[0]);
                            $city = trim($slices[1]);
                        } else {
                            $state = trim($slices[0]);
                        }

                        //CONSTRUCT JOB URL
                        if ($segment != 'DC' && $segment != 'D') {
                            if (!empty($city)) {
                                $final_url = $segmentUrl . '/' . strtolower(str_replace(' ', '_', $city)) . '/' . strtolower(str_replace(' ', '_', $state)) . '/' . $counter1;
                            } else {
                                $final_url = $segmentUrl . '/' . strtolower(str_replace(' ', '_', $state)) . '/' . $counter1;
                            }
                        } else {
                            $final_url = $segmentUrl;
                        }
                        $xml.= "<job>
                                        <title><![CDATA[$title]]></title>
                                        <date><![CDATA[$date]]></date>
                                        <referencenumber><![CDATA[$counter1]]></referencenumber>
                                        <url><![CDATA[$final_url]]></url>
                                        <country><![CDATA[US]]></country>
                                        <description><![CDATA[$description]]></description>
                                    </job>";

                        $counter2 = ($counter2 == ($total_titles - 1)) ? 0 : $counter2 + 1;
                        $counter3 = ($counter3 == ($total_urls - 1)) ? 0 : $counter3 + 1;
                        $counter4 = ($counter4 == ($total_descriptions - 1)) ? 0 : $counter4 + 1;
                        $counter5 = ($counter5 == ($total_city_states - 1)) ? 0 : $counter5 + 1;

                        $counter1++;
                    }
                }
            }

            $xml.='</source>';

            $basePath = \Yii::getAlias('@runtime/xml/history/');
            file_put_contents($basePath . $publisher . '_' . $file_date . '.xml', $xml);

            $this->redirect(['manager/xml-automation-history']);
        }
    }

    public function actionXmlAutomationHistory() {
        return $this->render('xml_automation/history');
    }

    public function actionDownloadFile() {
        $file = Yii::$app->request->get('file', '');

        if (empty($file)) {
            throw new \yii\web\NotFoundHttpException;
        }

        $path = Yii::getAlias('@runtime/xml/history/');

        return \Yii::$app->response->sendFile($path . $file);
    }

    public function actionRemoveFile() {
        $file = Yii::$app->request->get('file', '');

        if (!empty($file)) {
            $path = Yii::getAlias('@runtime/xml/history');
            if (file_exists($path . '/' . $file)) {
                @unlink($path . '/' . $file);
            }
        }

        return $this->redirect(['manager/xml-automation-history']);
    }

    public function actionShorturl() {
        $shortURLModel = new \app\models\ShortHr();
        $str = "";
        if ($shortURLModel->load(\Yii::$app->request->post()) && $shortURLModel->validate()) {
            $urls = explode("\n", $shortURLModel->real_url);
            $str = "<table border=\"1\" cellpadding=\"8\" class='table table-responsive'><tr><th>Title</th><th>Long URL</th><th>Short URL</th><th>Analytics URL</th></tr>";
            foreach ($urls as $url_title) {
                $trimed_url_title = trim($url_title);
                $parts = explode('|', $trimed_url_title);
                $url = trim($parts[1]);
                $title = trim($parts[0]);
                $current_url_check = \app\models\ShortHr::find()->where('real_url=:url', [':url' => $url])->one();
                if (empty($current_url_check)) {
                    $call = $this->getShortUrl($url);
                    $response = json_decode($call);
                    if (!is_object($response)) {
                        $this->render('short_url/shorturl', array('model' => $shortURLModel, 'error' => $call));
                        exit;
                    } else {
                        if (!empty($response->error)) {
                            $this->render('short_url/shorturl', array('model' => $shortURLModel, 'error' => "Error from Google API: " . $response->error->message));
                            exit;
                        }
                        $id = str_replace(array("https://goo.gl/", "http://goo.gl/"), "", $response->id);
                        $protocol = strpos($response->id, "https://") !== false ? "https://" : "http://";
                        $analytics_url = $protocol . "goo.gl/#analytics/goo.gl/$id/all_time";

                        $str .= "<tr><td>$title</td><td><a href=\"{$response->longUrl}\" target=\"_blank\">{$response->longUrl}</a></td><td><a href=\"{$response->id}\" target=\"_blank\">{$response->id}</a></td><td><a href=\"$analytics_url\" target=\"_blank\">$analytics_url</a></td></tr>";

                        //save to DB
                        $model = new \app\models\ShortHr();
                        $model->real_url = $url;
                        $model->job_title = $title;
                        $model->short_url = $response->id;
                        $model->analytic_url = $analytics_url;
                        $model->save(false);
                    }
                } else {
                    $str .= "<tr><td>{$current_url_check->job_title}</td><td><a href=\"{$current_url_check->real_url}\" target=\"_blank\">{$current_url_check->real_url}</a></td><td><a href=\"{$current_url_check->short_url}\" target=\"_blank\">{$current_url_check->short_url}</a></td><td><a href=\"{$current_url_check->analytic_url}\" target=\"_blank\">{$current_url_check->analytic_url}</a></td></tr>";
                }
            }
            $str .= "</table>";
//            }
        }

        return $this->render('short_url/shorturl', array('model' => $shortURLModel, 'table' => $str));
    }

    public function actionAnalyticsshorturl() {
        $current_url_check = \app\models\ShortHr::find()->all();
        $str = "";

        if (!empty($current_url_check)) {
            $str = "<table border=\"1\" cellpadding=\"8\" class='table table-responsive'><tr><th>Title</th><th>Long URL</th><th>Short URL</th><th>Analytics URL</th></tr>";

            foreach ($current_url_check as $job) {
                $str .= "<tr><td>{$job->job_title}</td><td><a href=\"{$job->real_url}\" target=\"_blank\">{$job->real_url}</a></td><td><a href=\"{$job->short_url}\" target=\"_blank\">{$job->short_url}</a></td><td><a href=\"{$job->analytic_url}\" target=\"_blank\">{$job->analytic_url}</a></td></tr>";
            }

            $str .= "</table>";
        } else {
            $str = "Nothing to show";
        }

        return $this->render('short_url/shorturl_analytics', array('table' => $str));
    }

    private function getShortUrl($url) {
        sleep(1);
        $url_encoded = urlencode($url);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.googleapis.com/urlshortener/v1/url?key=AIzaSyC1gLtynpOdw1XehaptfjtTwxApUdoelHQ",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"longUrl\": \"" . $url . "\"}",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public function actionTool($view = '') {
//        $this->layout = 'custom_layout';
        return $this->render('tool/' . $view);
    }

}
