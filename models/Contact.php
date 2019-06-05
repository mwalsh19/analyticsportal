<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_contact".
 *
 * @property integer $id_contact
 * @property string $name
 * @property string $job_title
 * @property string $time_zone
 * @property string $phone
 * @property string $mobile
 * @property string $email
 * @property string $location
 * @property string $create_date
 * @property integer $tbl_media_publisher_id_media_publisher
 *
 * @property ContactPublisher $tblPublisherIdPublisher
 * @property ContactTblCampaign[] $contactTblCampaigns
 * @property ContactCampaign[] $tblCampaignIdCampaigns
 */
class Contact extends base\ContactBase {

    public $imageFile;

    public function rules() {
        return [
            [['first_name', 'last_name', 'email', 'tbl_media_publisher_id_media_publisher'], 'required'],
            [['notes'], 'string'],
            [['create_date'], 'safe'],
            [['tbl_media_publisher_id_media_publisher'], 'integer'],
            [['first_name', 'last_name'], 'string', 'max' => 50],
            [['title', 'email'], 'string', 'max' => 100],
            [['phone', 'mobile'], 'string', 'max' => 20],
            [['ext'], 'string', 'max' => 10],
            [['time_zone'], 'string', 'max' => 5],
            [['email'], 'email'],
            [['photo'], 'string', 'max' => 45],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    public function attributeLabels() {
        return [
            'id_contact' => 'Id Contact',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'title' => 'Title',
            'email' => 'Email Address',
            'phone' => 'Phone Number',
            'ext' => 'Ext.',
            'mobile' => 'Mobile Number',
            'notes' => 'Notes',
            'time_zone' => 'Time Zone',
            'photo' => 'Upload Photo',
            'imageFile' => 'Upload Photo',
            'create_date' => 'Create Date',
            'tbl_media_publisher_id_media_publisher' => 'Media Publisher',
        ];
    }

    public function upload() {
        if ($this->validate()) {
            $previousImage = $this->photo;
            $randomString = \app\components\Utils::generateRandomString(35);
            $basePath = \Yii::getAlias('@webroot') . '/uploads/contact_photo/';

            $this->photo = $randomString . '.' . $this->imageFile->extension;

            if ($this->imageFile->saveAs($basePath . $this->photo)) {
                \app\components\Utils::deleteFileFromDirectory($basePath, array($previousImage));
                $this->imageFile = null;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    public static function find() {
        return new ContactQuery(get_called_class());
    }

}

class ContactQuery extends \yii\db\ActiveQuery {

    public function init() {
        $session = \Yii::$app->session;
        $id_company_user = $session['current_company']['id_company_user'];
        if ($id_company_user > 0) {
            $this->andWhere(['tbl_contact.id_company_user' => $id_company_user]);
        }
        parent::init();
    }
}
