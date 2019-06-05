<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "{{%media_publisher}}".
 *
 * @property integer $id_media_publisher
 * @property string $name
 * @property string $address
 * @property string $city
 * @property string $state
 * @property integer $zip_code
 * @property string $phone_number
 * @property string $ext
 * @property string $website_url
 * @property string $logo
 * @property integer $status
 * @property string $create_date
 *
 * @property Contact[] $contacts
 */
class MediaPublisherBase extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%media_publisher}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['address', 'website_url'], 'string'],
            [['zip_code', 'status'], 'integer'],
            [['create_date'], 'safe'],
            [['name'], 'string', 'max' => 60],
            [['city', 'logo'], 'string', 'max' => 45],
            [['state'], 'string', 'max' => 5],
            [['phone_number'], 'string', 'max' => 20],
            [['ext'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_media_publisher' => 'Id Media Publisher',
            'name' => 'Name',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'zip_code' => 'Zip Code',
            'phone_number' => 'Phone Number',
            'ext' => 'Ext',
            'website_url' => 'Website Url',
            'logo' => 'Logo',
            'status' => 'Status',
            'create_date' => 'Create Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts() {
        return $this->hasMany(ContactBase::className(), ['tbl_media_publisher_id_media_publisher' => 'id_media_publisher']);
    }

}
