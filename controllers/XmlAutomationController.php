<?php

namespace app\controllers;

use Yii;

class XmlAutomationController extends \app\components\CustomController {

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

    public function actionHistory() {
        return $this->render('history');
    }

    public function actionReview() {
        $request = \Yii::$app->request;
        $session = \Yii::$app->session;

        $model = new \app\models\ToolForm(['scenario' => 'publisher']);

        if ($request->isPost && $model->load($request->post())) {
            if ($model->validate()) {
                $session->set('publisher', $model->publisher);
                $session->set('currentSegmentIndex', 0);

                $this->redirect(['xml-automation/segments']);
            } else {
                $session->setFlash('fail', 'Please select a Company and Publisher before continue.');
            }
        }

        return $this->render('review', ['model' => $model]);
    }

    public function actionSegments() {
        ini_set('max_execution_time', 10);
        ini_set("memory_limit", "256M");

        $request = \Yii::$app->request;
        $session = \Yii::$app->session;
        $cache = \Yii::$app->cache;

        $id_publisher = $session->get('publisher', 0);
        $prev_segment = $request->get('segment', 0);
        $segmentPos = $session->get('currentSegmentIndex', 0);

        $tool_model = new \app\models\ToolForm(['scenario' => 'segments']);

        /* CACHING DATA */

        $publisherModel = \app\models\XmlPublisher::find()->where('id_publisher = :id_publisher', [
                    ':id_publisher' => $id_publisher
                ])->with('tblXmlSegmentIdSegments')->one();

        $cache->set('publisherObject', $publisherModel);

        $isJibe = false;
        if (strpos(strtolower($publisherModel->name), 'jibe') !== false) {
            $isJibe = true;
        }

        if ($cache->get('cityStateArray') == false && $isJibe) {
            $sql = 'SELECT * FROM tbl_city_state ce JOIN tbl_jibe j ON ce.id_city_state = j.id_city_state WHERE j.craigslist_market != "" ORDER BY ce.state ASC';
            $cityStateModels = \Yii::$app->db->createCommand($sql)->queryAll();
            $cache->set('cityStateArray', $cityStateModels, 900);
        } else if ($cache->get('cityStateArray') == false && !$isJibe) {
            $cityStateModels = \app\models\base\CityStateBase::find()->orderBy('state ASC')->all();
            $cache->set('cityStateArray', $cityStateModels, 900);
        }

        if (!empty($prev_segment) && $segmentPos > 0) {
            $segmentPos--;
            $session->set('currentSegmentIndex', $segmentPos);
        }

        $cityStateArray = $cache->get('cityStateArray');
        $segmentArray = $publisherModel->tblXmlSegmentIdSegments;
        $publisherObject = $cache->get('publisherObject');
        $segment = $segmentArray[$segmentPos];

        //Get session publisher segments, filtered by status
        $sql2 = 'tbl_xml_publisher_id_publisher = :id_publisher AND tbl_xml_segment_id_segment = :id_segment AND status=1';
        $sessionStorage = \app\models\SessionStorage::find()->where($sql2, [
                    ':id_publisher' => $id_publisher,
                    ':id_segment' => $segment->id_segment
                ])->one();

        if (empty($sessionStorage)) {
            $sessionStorage = new \app\models\SessionStorage();
        } else {
            $tool_model->loop_count = $sessionStorage->loop_count;
        }

        if ($request->isPost) {
            $cityStates = $request->post('city_state', []);
            $tool_model_2 = new \app\models\ToolForm(['scenario' => 'segments']);
            $tool_model_2->load($request->post(), 'ToolForm');

            if (count($cityStates) > 0 && $tool_model_2->validate()) {
                if (empty($sessionStorage)) {
                    $sessionStorage = new \app\models\SessionStorage();
                }
                $sessionStorage->tbl_xml_publisher_id_publisher = $id_publisher;
                $sessionStorage->tbl_xml_segment_id_segment = $segment->id_segment;
                $sessionStorage->loop_count = $tool_model_2->loop_count;
                $sessionStorage->payload = serialize($cityStates);
                $sessionStorage->save(false);

                $segmentPos++;

                if ($segmentPos == count($segmentArray)) {
                    $session->remove('currentSegmentIndex');
                    $result = $this->XML();
                    if (!empty($result)) {
                        Yii::$app->session->setFlash('fail', $result['msg']);
                    }
                } else {
                    $session->set('currentSegmentIndex', $segmentPos);
                    $segment = $segmentArray[$segmentPos];
                }
            } else {
                //set a flash ans show alert
                $tool_model->loop_count = $tool_model_2->loop_count;

                $msg = '';
                if (empty($cityStates) && empty($tool_model->loop_count)) {
                    $msg = 'Please select at least one state or city and the loop count cant\'n be blank';
                } else if (empty($cityStates)) {
                    $msg = 'Please select at least one state or city';
                } else if (empty($tool_model->loop_count)) {
                    $msg = 'The loop count cant\'n be blank';
                }

                if (strlen($msg) > 0) {
                    $session->setFlash('fail', $msg);
                }
            }
        }

        return $this->render('segments', [
                    'tool_model' => $tool_model,
                    'cityStateList' => $cityStateArray,
                    'sessionStorage' => $sessionStorage,
                    'segment' => $segment,
                    'segmentPos' => $segmentPos,
                    'publisherObject' => $publisherObject
                        ]
        );
    }

    private function XML() {
        $session = \Yii::$app->session;
        $cache = \Yii::$app->cache;
        $id_publisher = $session->get('publisher', '');
        $publisherObject = $cache->get('publisherObject');
        //
        $strToTime = strtotime('now');
        $date = date('D, d M Y h:i:s e', $strToTime);
        $count1 = 1;
        $jobs = '';
        $root = '';
        $extra_fields = '';
        $jobLayout = '';
        $phone = '';

        //Get Session Records, only active records
        $sessionRecords = \app\models\SessionStorage::find()->where('tbl_xml_publisher_id_publisher = :id_publisher AND status=1', [':id_publisher' => $id_publisher])->all();

        //Get Options for current publisher,in this case for xml root
        $xmlOptions = \app\models\XmlOptions::find()->where('tbl_xml_publisher_id_publisher=:id_publisher', [':id_publisher' => $id_publisher])->one();
        if (empty($xmlOptions) && empty($xmlOptions->root)) {
            return ['msg' => 'Oops! You need add a Root tag for xml layout, please check your xml layout.'];
        } else {
            $root = trim($xmlOptions->root);
            $extra_fields = trim($xmlOptions->extra_fields);
        }

        //Get Segments
        $segments = \app\models\XmlSegment::find()->all();
        $segmentArray = [];
        if (!empty($segments)) {
            $total = count($segments);
            for ($index1 = 0; $index1 < $total; $index1++) {
                $segmentArray[$segments[$index1]->id_segment] = $segments[$index1]->name;
            }
        }

        $totalSessionRecords = count($sessionRecords);
        if ($totalSessionRecords > 0) {

            //Know is jibe publisher
            $isJibe = false;
            if (strpos(strtolower($publisherObject->name), 'jibe') !== false) {
                $isJibe = true;
            }

            //Init session records loop
            /*
             * loop_count (Number of repetitions)
             * payload (Serialized payload)
             * tbl_xml_segment_id_segment (ID of segment)
             * tbl_xml_publisher_id_publisher (ID of publisher)
             */
            for ($index = 0; $index < $totalSessionRecords; $index++) {
                $sessionObject = $sessionRecords[$index];

                $idSegment = $sessionObject->tbl_xml_segment_id_segment;
                $loopCount = $sessionObject->loop_count;

                //Get city state array from session storage
                $payloadString = $sessionObject->payload;
                $cityStateArray = !empty($payloadString) ? unserialize($payloadString) : '';
                $totalCityStates = count($cityStateArray);
                $totalJobs = $totalCityStates * $loopCount;

                //Get XML layou for current segment
                $xml_layout = \app\models\XmlLayout::find()->where('tbl_xml_publisher_id_publisher=:id_publisher AND tbl_xml_segment_id_segment=:id_segment', [
                            ':id_publisher' => $id_publisher,
                            ':id_segment' => $idSegment,
                        ])->one();

                if (empty($xml_layout->layout)) {
                    return ['msg' => "The <strong>{$segmentArray[$idSegment]}</strong> has no layout, please check before continue."];
                }

                //Try to load XML
                $xml = simplexml_load_string($xml_layout->layout);
                if ($xml == false) {
                    return ['msg' => "An error has occurred when try to load xml layout for <strong>{$segmentArray[$idSegment]}</strong> segment."];
                }

                //Convert XML object to plain text and FORMAT this
                $job = $xml->asXML();
                $jobString = str_replace('<?xml version="1.0"?>', '', trim($job));
                $jobLayout = str_replace(' type="repeat"', '', $jobString);

                if (!strlen($jobLayout)) {
                    return ['msg' => "An error has occurred when try to create xml layout for <strong>{$segmentArray[$idSegment]}</strong> segment."];
                }

                //Check if the current segment has phone number
                $hasPhone = \app\models\Phone::find()->where(
                                'tbl_xml_publisher_id_publisher=:id_publisher AND tbl_xml_segment_id_segment=:id_segment', [
                            ":id_publisher" => $id_publisher,
                            ":id_segment" => $idSegment
                                ]
                        )->one();

                if (!empty($hasPhone)) {
                    $phone = $hasPhone->phone;
                } else {
                    return ['msg' => "An error has occurred when try to search phone number for <strong>{$segmentArray[$idSegment]}</strong> segment."];
                }

                //Get titles for current segment
                $titles = \app\models\Title::find()->where(
                                'tbl_xml_publisher_id_publisher=:id_publisher AND tbl_xml_segment_id_segment=:id_segment', [
                            ":id_publisher" => $id_publisher,
                            ":id_segment" => $idSegment
                                ]
                        )->all();

                //Get urls for current segment
                $urls = \app\models\Url::find()->where(
                                'tbl_xml_publisher_id_publisher=:id_publisher AND tbl_xml_segment_id_segment=:id_segment', [
                            ":id_publisher" => $id_publisher,
                            ":id_segment" => $idSegment
                                ]
                        )->all();

                //Get descriptions for current segment
                $descriptions = \app\models\Description::find()->where(
                                'tbl_xml_publisher_id_publisher=:id_publisher AND tbl_xml_segment_id_segment=:id_segment', [
                            ":id_publisher" => $id_publisher,
                            ":id_segment" => $idSegment
                                ]
                        )->all();

                if (!empty($titles) && !empty($urls) && !empty($descriptions)) {
                    $totalTitles = count($titles);
                    $totalUrls = count($urls);
                    $totalDescriptions = count($descriptions);

                    $title_counter = 0;
                    $url_counter = 0;
                    $desc_counter = 0;
                    $citystate_counter = 0;

                    for ($index2 = 0; $index2 < $totalJobs; $index2++) {
                        $final_url = '';
                        $city = '';
                        $state = '';
                        $jibe_code = '';

                        $title = $titles[$title_counter]->title;
                        $url = $urls[$url_counter]->url;
                        $description = $descriptions[$desc_counter]->description;
                        $currentCityState = explode('_', $cityStateArray[$citystate_counter]);

                        if ($isJibe && count($currentCityState) == 3) {
                            $state = trim($currentCityState[0]);
                            $city = trim($currentCityState[1]);
                            $jibe_code = trim($currentCityState[2]);
                        } else if (!$isJibe && count($currentCityState) == 3) {
                            $state = trim($currentCityState[0]);
                            $city = trim($currentCityState[1]);
                        } else {
                            $state = trim($currentCityState[0]);
                        }

                        $final_state = !empty($state) ? ucwords(strtolower($state)) : $state;
                        $final_city = !empty($city) ? ucwords(strtolower($city)) : $city;
                        $array_search = array(
                            ' ',
                            ',',
                            '-',
                            '/',
                        );
                        $array_for_replace = array(
                            '',
                            '_',
                            '_',
                            '_'
                        );

                        if (!empty($city)) {
                            $final_url = $url . '/' . strtolower(str_replace($array_search, $array_for_replace, $city)) . '/' . strtolower(str_replace($array_search, $array_for_replace, $state)) . '/' . $count1;
                        } else {
                            $final_url = $url . '/' . strtolower(str_replace($array_search, $array_for_replace, $state)) . '/' . $count1;
                        }

                        if ($jibe_code == 'na') {
                            $jibe_code = '';
                        }

                        $final_description = str_replace("%%url%%", $final_url, $description);
                        $format_1 = str_replace('%%date%%', $date, $jobLayout);
                        $format_2 = str_replace('%%increment%%', $count1, $format_1);
                        $format_3 = str_replace('%%title%%', $title, $format_2);
                        $format_4 = str_replace('%%desc%%', $final_description, $format_3);
                        $format_5 = str_replace('%%url%%', $final_url, $format_4);
                        $format_6 = str_replace('%%city%%', $final_city, $format_5);
                        $format_7 = str_replace('%%state%%', $final_state, $format_6);
                        $format_8 = str_replace('%%phone%%', $phone, $format_7);
                        $jobs.= str_replace('%%jibe_code%%', $jibe_code, $format_8);

                        $title_counter = ($title_counter == ($totalTitles - 1)) ? 0 : $title_counter + 1;
                        $url_counter = ($url_counter == ($totalUrls - 1)) ? 0 : $url_counter + 1;
                        $desc_counter = ($desc_counter == ($totalDescriptions - 1)) ? 0 : $desc_counter + 1;
                        $citystate_counter = ($citystate_counter == ($totalCityStates - 1)) ? 0 : $citystate_counter + 1;

                        $count1++;
                    }
                } else {
                    return ['msg' => "Please check if the titles, descriptions and URLs are complete for <strong>{$segmentArray[$idSegment]}</strong> segment."];
                }
            }
        }

        $finalXml = '<?xml version="1.0" encoding="utf-8"?>';
        $finalXml.= "\n<$root>{$extra_fields}{$jobs}</$root>";
        $finalXml = str_replace(['%%totaljobs%%', '%%date%%'], [$count1, $date], $finalXml);

        $basePath = \Yii::getAlias('@runtime/xml/history/');
        $fileName = str_replace(' ', '', $publisherObject->name) . '_' . $strToTime . '.xml';
        file_put_contents($basePath . $fileName, $finalXml);

        Yii::$app->session->setFlash('success', "The XML: <strong>{$fileName}</strong> was successfully created");
        $this->redirect(['xml-automation/history']);
    }

    public function actionGetPublishers() {
        $id = \Yii::$app->request->get('id_company');
        $options = '';
        if (!empty($id)) {
            $publishers = \app\models\XmlPublisher::find()->where('tbl_company_id_company=:id_company', [':id_company' => $id])->orderBy('name ASC')->all();

            if (!empty($publishers)) {
                $options.= '<option value selected>SELECT PUBLISHER</option>';
                for ($index = 0; $index < count($publishers); $index++) {
                    $publisherObject = $publishers[$index];
                    $options.= "<option value='{$publisherObject->id_publisher}'>{$publisherObject->name}</option>";
                }
            }
        }

        $obj = new \stdClass();
        $obj->publishers = $options;

        header('Content-Type: application/json');
        echo json_encode($obj);
    }

    public function actionGetSegments() {
        $id = \Yii::$app->request->get('id_publisher');
        $options = '';

        if (!empty($id)) {
            $publisher = \app\models\XmlPublisher::find()->where('id_publisher=:id_publisher', [':id_publisher' => $id])->with('tblXmlSegmentIdSegments')->one();

            if (!empty($publisher)) {
                if (!empty($publisher->tblXmlSegmentIdSegments)) {
                    $options.= '<option value selected>SELECT SEGMENT</option>';

                    for ($index = 0; $index < count($publisher->tblXmlSegmentIdSegments); $index++) {
                        $segmentObject = $publisher->tblXmlSegmentIdSegments[$index];
                        $options.= "<option value='{$segmentObject->id_segment}'>{$segmentObject->name}</option>";
                    }
                }
            }
        }

        $obj = new \stdClass();
        $obj->segments = $options;

        header('Content-Type: application/json');
        echo json_encode($obj);
    }

    public function actionGetSegmentRelations() {
        $request = \Yii::$app->request;
        $id_segment = $request->get('id_segment');
        $id_publisher = $request->get('id_publisher');

        $titles = '';
        $descriptions = '';
        $urls = '';
        if (!empty($id_segment) && !empty($id_publisher)) {

            $titleModels = \app\models\Title::find()->where('tbl_xml_segment_id_segment=:id_segment AND tbl_xml_publisher_id_publisher=:id_publisher', [
                        ':id_segment' => $id_segment,
                        ':id_publisher' => $id_publisher,
                    ])->all();

            $descriptionModels = \app\models\Description::find()->where('tbl_xml_segment_id_segment=:id_segment AND tbl_xml_publisher_id_publisher=:id_publisher', [
                        ':id_segment' => $id_segment,
                        ':id_publisher' => $id_publisher,
                    ])->all();
            $urlModels = \app\models\Url::find()->where('tbl_xml_segment_id_segment=:id_segment AND tbl_xml_publisher_id_publisher=:id_publisher', [
                        ':id_segment' => $id_segment,
                        ':id_publisher' => $id_publisher,
                    ])->all();

            if (!empty($titleModels)) {
                for ($index = 0; $index < count($titleModels); $index++) {
                    $titleObject = $titleModels[$index];
                    $titles.= "<p>{$titleObject->title}</p>";
                }
            }

            if (!empty($descriptionModels)) {
                $counter = 1;
                $descriptions.= "<table class='table table-bordered table-striped table-responsive table-condensed'>";
                for ($index = 0; $index < count($descriptionModels); $index++) {
                    $descriptionObject = $descriptionModels[$index];
                    $current_description = '';

                    if ($descriptionObject->contain_html) {
                        $current_description = '<a href="' . \yii\helpers\Url::to(['description/detail', 'id' => $descriptionObject->id_description]) . '" class="btn btn-primary btn-sm">More</a>';
                    } else {
                        $current_description = $descriptionObject->description;
                    }

                    $descriptions.= "<tr><td style='width: 30px;'>{$counter}</td><td>{$current_description}</tr></td>";
                    $counter++;
                }
                $descriptions.= "</table>";
            }
            if (!empty($urlModels)) {
                for ($index = 0; $index < count($urlModels); $index++) {
                    $urlnObject = $urlModels[$index];
                    $urls.= "<p>{$urlnObject->url}</p>";
                }
            }
        }

        $obj = new \stdClass();
        $obj->titles = strlen($titles) > 0 ? $titles : 'No results found';
        $obj->descriptions = strlen($descriptions) > 0 ? $descriptions : 'No results found';
        $obj->urls = strlen($urls) > 0 ? $urls : 'No results found';

        header('Content-Type: application/json');
        echo json_encode($obj);
    }

    public function actionDownloadFile() {
        $file = Yii::$app->request->get('file', '');

        if (empty($file)) {
            throw new \yii\web\NotFoundHttpException;
        }

        $path = Yii::getAlias('@runtime/xml/history/');

        return \Yii::$app->response->sendFile($path . $file);
    }

    public function actionDeleteFile() {
        $file = Yii::$app->request->get('file', '');

        if (!empty($file)) {
            $path = Yii::getAlias('@runtime/xml/history');
            if (file_exists($path . '/' . $file)) {
                @unlink($path . '/' . $file);
                \Yii::$app->session->setFlash('success', 'Well done, the XML was deleted successfully.');
            } else {
                \Yii::$app->session->setFlash('fail', 'Oops!an error has occurred when try to delete the xml');
            }
        }

        return $this->redirect(['xml-automation/history']);
    }

    public function actionGetXmlLayout() {
        $this->layout = false;
        $id_publisher = Yii::$app->request->get('id_publisher', '');
        $id_segment = Yii::$app->request->get('id_segment', '');

        $model = \app\models\XmlLayout::find()->where('tbl_xml_publisher_id_publisher = :id_publisher AND tbl_xml_segment_id_segment = :id_segment', [
                    ':id_publisher' => $id_publisher,
                    ':id_segment' => $id_segment
                ])->one();


        $obj = new \stdClass();
        $obj->xml_layout = !empty($model->layout) ? $model->layout : '';

        header('Content-Type: application/json');
        echo json_encode($obj);
    }

    public function actionSaveXmlLayout() {
        $this->layout = false;
        $id_publisher = Yii::$app->request->post('id_publisher', '');
        $id_segment = Yii::$app->request->post('id_segment', '');
        $xml_ayout = Yii::$app->request->post('xml_layout', '');

        $model = \app\models\XmlLayout::find()->where('tbl_xml_publisher_id_publisher = :id_publisher AND tbl_xml_segment_id_segment = :id_segment', [
                    ':id_publisher' => $id_publisher,
                    ':id_segment' => $id_segment
                ])->one();


        if (empty($model)) {
            $model = new \app\models\XmlLayout();
        }

        $model->layout = trim($xml_ayout);
        $model->tbl_xml_publisher_id_publisher = $id_publisher;
        $model->tbl_xml_segment_id_segment = $id_segment;

        $obj = new \stdClass();

        if ($model->save()) {
            $obj->status = 'OK';
        } else {
            $obj->status = 'ERROR';
        }

        header('Content-Type: application/json');
        echo json_encode($obj);
    }

    public function actionGetPhone() {
        $this->layout = false;
        $id_publisher = Yii::$app->request->get('id_publisher', '');
        $id_segment = Yii::$app->request->get('id_segment', '');

        $model = \app\models\Phone::find()->where('tbl_xml_publisher_id_publisher = :id_publisher AND tbl_xml_segment_id_segment = :id_segment', [
                    ':id_publisher' => $id_publisher,
                    ':id_segment' => $id_segment,
                ])->one();

        $obj = new \stdClass();
        $obj->phone = !empty($model->phone) ? $model->phone : '';

        header('Content-Type: application/json');
        echo json_encode($obj);
    }

    public function actionSavePhone() {
        $this->layout = false;
        $id_publisher = Yii::$app->request->post('id_publisher', '');
        $id_segment = Yii::$app->request->post('id_segment', '');
        $phone = Yii::$app->request->post('phone', '');

        $model = \app\models\Phone::find()->where('tbl_xml_publisher_id_publisher = :id_publisher AND tbl_xml_segment_id_segment = :id_segment', [
                    ':id_publisher' => $id_publisher,
                    ':id_segment' => $id_segment,
                ])->one();


        if (empty($model)) {
            $model = new \app\models\Phone();
        }

        $model->phone = $phone;
        $model->tbl_xml_publisher_id_publisher = $id_publisher;
        $model->tbl_xml_segment_id_segment = $id_segment;

        $obj = new \stdClass();

        if ($model->save()) {
            $obj->status = 'OK';
        } else {
            $obj->status = 'ERROR';
        }

        header('Content-Type: application/json');
        echo json_encode($obj);
    }

    public function actionGetXmlOptions() {
        $this->layout = false;
        $id_publisher = Yii::$app->request->get('id_publisher', '');

        $model = \app\models\XmlOptions::find()->where('tbl_xml_publisher_id_publisher = :id_publisher', [
                    ':id_publisher' => $id_publisher
                ])->one();

        $obj = new \stdClass();
        $obj->xml_root = !empty($model->root) ? $model->root : '';
        $obj->xml_extra_fields = !empty($model->extra_fields) ? $model->extra_fields : '';

        header('Content-Type: application/json');
        echo json_encode($obj);
    }

    public function actionSaveXmlOptions() {
        $this->layout = false;
        $id_publisher = Yii::$app->request->post('id_publisher', '');
        $root = Yii::$app->request->post('xml_root', '');
        $extra_fields = Yii::$app->request->post('xml_extra_fields', '');

        $model = \app\models\XmlOptions::find()->where('tbl_xml_publisher_id_publisher = :id_publisher', [
                    ':id_publisher' => $id_publisher
                ])->one();

        if (empty($model)) {
            $model = new \app\models\XmlOptions();
        }

        $model->root = trim($root);
        $model->extra_fields = trim($extra_fields);
        $model->tbl_xml_publisher_id_publisher = $id_publisher;

        $obj = new \stdClass();
        if ($model->save()) {
            $obj->status = 'OK';
        } else {
            $obj->status = 'ERROR';
        }

        header('Content-Type: application/json');
        echo json_encode($obj);
    }

}
