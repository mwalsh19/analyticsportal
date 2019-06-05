<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_xml_publisher".
 *
 * @property integer $id_publisher
 * @property string $name
 * @property string $create_date
 * @property integer $tbl_company_id_company
 *
 * @property XmlCampaign[] $xmlCampaigns
 * @property Company $tblCompanyIdCompany
 */
class XmlPublisher extends base\XmlPublisherBase {

    public function attributeLabels() {
        return [
            'tbl_company_id_company' => 'Company',
        ];
    }

    public static function getSegments() {
        $id = \Yii::$app->request->get('id_publisher');
        $options = '';

        if (!empty($id)) {
            $xmlPublisher = new XmlPublisher();
            $publisher = $xmlPublisher->find()->where('id_publisher=:id_publisher', [':id_publisher' => $id])->with('tblXmlSegmentIdSegments')->one();
            if (!empty($publisher)) {
                if (!empty($publisher->tblXmlSegmentIdSegments)) {
                    $options.= '<option value selected>Select a segment</option>';

                    for ($index = 0; $index < count($publisher->tblXmlSegmentIdSegments); $index++) {
                        $segmentObject = $publisher->tblXmlSegmentIdSegments[$index];
                        $options.= "<option value='{$segmentObject->id_segment}'>{$segmentObject->name}</option>";
                    }
                }
            }
        }
        return $options;
    }

}
