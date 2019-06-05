<?php

namespace app\models;

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
 * @property string $create_date
 * @property string $tenstreet_referrer_part
 *
 * @property Contact[] $contacts
 */
class MediaPublisher extends base\MediaPublisherBase {

    public $imageFile;

    public function rules() {
        return [
            [['name'], 'required'],
            [['address', 'website_url'], 'string'],
            [['zip_code'], 'integer'],
            [['create_date', 'status', 'tenstreet_referrer_part'], 'safe'],
            [['name'], 'string', 'max' => 60],
            [['city', 'logo'], 'string', 'max' => 45],
            [['state'], 'string', 'max' => 5],
            [['phone_number'], 'string', 'max' => 20],
            [['ext'], 'string', 'max' => 10],
            [['website_url'], 'url'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['imageFile'], 'dimensionValidation']
        ];
    }

    public function attributeLabels() {
        return [
            'id_media_publisher' => 'Id Media Publisher',
            'name' => 'Publisher Name',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'zip_code' => 'Zip Code',
            'phone_number' => 'Phone Number',
            'ext' => 'Ext',
            'website_url' => 'Website URL',
            'logo' => 'Logo',
            'tenstreet_referrer_part' => 'Tenstreet (for referrer code)',
            'imageFile' => 'Upload Logo (max width 275px)',
            'status' => 'Status',
            'create_date' => 'Create Date',
        ];
    }

    public function upload() {
        if ($this->validate()) {
            $previousImage = $this->logo;
            $randomString = \app\components\Utils::generateRandomString(35);
            $basePath = \Yii::getAlias('@webroot') . '/uploads/publisher_logo/';

            $this->logo = $randomString . '.' . $this->imageFile->extension;

            if ($this->imageFile->saveAs($basePath . $this->logo)) {
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

    public function dimensionValidation($attribute, $param) {
        if (is_object($this->imageFile)) {
            list($width, $height) = getimagesize($this->imageFile->tempName);
            if ($width > 275) {
                $this->addError('imageFile', 'The logo can\'t be more than 275px wide');
            }
        }
    }

    public static function find() {
        return new MediaPublisherQuery(get_called_class());
    }

}

class MediaPublisherQuery extends \yii\db\ActiveQuery {

    public function init() {
        $session = \Yii::$app->session;
        $id_company_user = $session['current_company']['id_company_user'];
        if ($id_company_user > 0) {
            $this->andWhere(['id_company_user' => $id_company_user]);
        }
        parent::init();
    }
}
