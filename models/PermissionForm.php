<?php

namespace app\models;

use Yii;
use yii\base\Model;

class PermissionForm extends Model {

    public $permission_name;
    public $permission_description;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['permission_name'], 'required'],
            [['permission_name'], 'validatePermissionName'],
            [['permission_description'], 'safe']
        ];
    }

    public function validatePermissionName($attribute, $params) {
        if (preg_match('/\s/', $this->$attribute) > 0) {
            $this->addError($attribute, 'Permission Name cannot contain blank spaces');
        }
    }

}
