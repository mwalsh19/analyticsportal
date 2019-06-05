<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tbl_xml_publisher".
 *
 * @property integer $id_publisher
 * @property string $name
 * @property string $create_date
 * @property integer $tbl_company_id_company
 *
 * @property Attribute[] $attributes
 * @property Description[] $descriptions
 * @property Title[] $titles
 * @property Url[] $urls
 * @property XmlLayout[] $xmlLayouts
 * @property Company $tblCompanyIdCompany
 * @property XmlSegmentHasTblXmlPublisher[] $xmlSegmentHasTblXmlPublishers
 * @property XmlSegment[] $tblXmlSegmentIdSegments
 */
class XmlPublisherBase extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_xml_publisher';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'tbl_company_id_company'], 'required'],
            [['create_date'], 'safe'],
            [['tbl_company_id_company'], 'integer'],
            [['name'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_publisher' => 'Id Publisher',
            'name' => 'Name',
            'create_date' => 'Create Date',
            'tbl_company_id_company' => 'Tbl Company Id Company',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeneralAttributes() {
        return $this->hasMany(\app\models\Attribute::className(), ['tbl_xml_publisher_id_publisher' => 'id_publisher']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDescriptions() {
        return $this->hasMany(\app\models\Description::className(), ['tbl_xml_publisher_id_publisher' => 'id_publisher']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTitles() {
        return $this->hasMany(\app\models\Title::className(), ['tbl_xml_publisher_id_publisher' => 'id_publisher']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUrls() {
        return $this->hasMany(\app\models\Url::className(), ['tbl_xml_publisher_id_publisher' => 'id_publisher']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXmlLayouts() {
        return $this->hasMany(\app\models\XmlLayout::className(), ['tbl_xml_publisher_id_publisher' => 'id_publisher']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblCompanyIdCompany() {
        return $this->hasOne(\app\models\Company::className(), ['id_company' => 'tbl_company_id_company']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXmlSegmentHasTblXmlPublishers() {
        return $this->hasMany(XmlSegmentHasTblXmlPublisher::className(), ['tbl_xml_publisher_id_publisher' => 'id_publisher']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblXmlSegmentIdSegments() {
        return $this->hasMany(\app\models\XmlSegment::className(), ['id_segment' => 'tbl_xml_segment_id_segment'])->viaTable('tbl_xml_segment_has_tbl_xml_publisher', ['tbl_xml_publisher_id_publisher' => 'id_publisher']);
    }

}
