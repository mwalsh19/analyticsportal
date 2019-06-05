<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%company_user}}".
 *
 * @property integer $id_company_user
 * @property string $name
 * @property string $create_date
 */
class CompanyUser extends base\CompanyUserBase {

    public $imageFile;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['logo', 'tenstreet_company_id'], 'safe'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png', 'checkExtensionByMimeType' => false],
            [['imageFile'], 'dimensionValidation']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_company_user' => 'Id Company User',
            'name' => 'Company name',
            'tenstreet_company_id' => 'Tenstreet ID',
            'imageFile' => 'Company logo  (max width 200px)',
            'create_date' => 'Create Date',
        ];
    }

    public function upload() {
        if ($this->validate()) {
            $previousImage = $this->logo;
            $randomString = \app\components\Utils::generateRandomString(35);
            $basePath = \Yii::getAlias('@webroot') . '/uploads/company_logo/';

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
            if ($width > 200) {
                $this->addError('imageFile', 'The logo can not be more than 200 pixels wide');
            }
        }
    }

}
