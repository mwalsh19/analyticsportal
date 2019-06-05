<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RoleForm extends Model {

    public $role_name;
    public $role_description;
    public $roles;
    public $permissions;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['role_name'], 'required', 'on' => 'create'],
            [['role_name'], 'validateRoleName'],
            [['permissions', 'role_name'], 'safe', 'on' => 'permissions'],
            [['role_description'], 'safe']
        ];
    }

    public function validateRoleName($attribute, $params) {
        if (preg_match('/\s/', $this->$attribute) > 0) {
            $this->addError($attribute, 'Role Name cannot contain blank spaces');
        }
    }

}
