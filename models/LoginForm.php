<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model {

    public $email;
    public $password;
    public $rememberMe = false;
    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // email and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional email-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided email and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            $rememberMe = $this->rememberMe ? 3600 * 24 * 30 : 0;
            //set initial user company and logo
            $user = $this->getUser();
            $login = Yii::$app->user->login($user, $rememberMe);
            if($login) {
                $this->getUserCompanies($user);
                return true;
            } else {
                return false;
            }

        }
        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    public function getUser() {
        if ($this->_user === false) {
            $this->_user = User::findByUserEmail($this->email);
        }

        return $this->_user;
    }
    
    private function getUserCompanies($userObject) {
        $logo = \yii\helpers\Url::base() . '/manager/images/beta_logo.png';
        $session = \Yii::$app->session;
        $has_companies = !empty($userObject->userHasTblCompanyUsers)? count($userObject->userHasTblCompanyUsers) : 0;

        if ($has_companies) {
            $companyObject = $userObject->userHasTblCompanyUsers[0];
            $company = $companyObject->tblCompanyUserIdCompanyUser;
            $id_company = $company->id_company_user;
            $session['companies'] = $userObject->userHasTblCompanyUsers;

            if (!empty($company->logo)) {
                $logo = \yii\helpers\Url::base() . '/uploads/company_logo/' . $company->logo;
            }
        }
        
        $session['current_company'] = ['logo' => $logo, 'id_company_user' => $id_company];
    }

}
