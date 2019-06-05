<?php

namespace app\components;

use Yii;
use \SendGrid;

class SendGridEmail {

    const NO_REPLY_TO = 'no-reply@swiftportal.com';
    const FROM = self::NO_REPLY_TO;
    const FROM_NAME = 'Swift Portal';

    public static function forgotPassword($token = '', $user_email = '') {

        if (empty($token) && empty($user_email)) {
            return false;
        }

        $sendgrid = new SendGrid(Yii::$app->params['SENDGRID_APIKEY']);
        $email = new SendGrid\Email();

        $loginUrl = \yii\helpers\Url::base(true) . '/user/forgot-password-verify?token=' . $token;
        $subject = 'Forgot your password';
        $templateId = '13a88531-f413-4174-969a-9b7f4e690ce7';


        $html = '
        <p><a href="' . $loginUrl . '" style="font-size:13px;
        font-weight:100;
        font-family:Helvetica,Arial,sans-serif;
        text-transform:uppercase;
        text-align:center;
        letter-spacing:1px;
        text-decoration:none;
        background:#398bce;
        display:block;
        padding: 15px 10px;
        border-radius:3px;
        width: 180px;
        color:#ffffff" target="_blank">Change Password</a></p>
    ';

        $email->addTo(trim($user_email));
        //   $email->addTo('jjmaceda@gmail.com');
        $email->setReplyTo(self::NO_REPLY_TO);
        $email->setFrom(self::FROM);
        $email->setFromName(self::FROM_NAME);
        $email->setSubject($subject);
        $email->setHtml($html);
        $email->setTemplateId($templateId);

        try {
            $sendgrid->send($email);
        } catch (\SendGrid\Exception $e) {
            echo $e->getCode();
            foreach ($e->getErrors() as $er) {
                echo $er;
            }
        }
    }

    public static function verifyUserAccount($user_email, $password, $token) {

        if (empty($user_email) && empty($password) && empty($token)) {
            return false;
        }

        $sendgrid = new SendGrid(Yii::$app->params['SENDGRID_APIKEY']);
        $email = new SendGrid\Email();

        $loginUrl = \yii\helpers\Url::base(true) . '/user/verify-user-account?token=' . $token;
        $subject = 'Welcome to SwiftPortal';
        $templateId = 'bfa1dac1-2c01-4ce9-aef0-da6110dc5bfb';


        $html = '
        <p>
            <strong>User:</strong> ' . $user_email . '<br>
            <strong>Password:</strong> ' . $password . '
        </p>
        <p><a href="' . $loginUrl . '" style="font-size:13px;
        font-weight:100;
        font-family:Helvetica,Arial,sans-serif;
        text-transform:uppercase;
        text-align:center;
        letter-spacing:1px;
        text-decoration:none;
        background:#398bce;
        display:block;
        padding: 15px 10px;
        border-radius:3px;
        width: 180px;
        color:#ffffff" target="_blank">Verify Account</a></p>
    ';

        $email->addTo(trim($user_email));
        //   $email->addTo('jjmaceda@gmail.com');
        $email->setReplyTo(self::NO_REPLY_TO);
        $email->setFrom(self::FROM);
        $email->setFromName(self::FROM_NAME);
        $email->setSubject($subject);
        $email->setHtml($html);
        $email->setTemplateId($templateId);

        try {
            $sendgrid->send($email);
        } catch (\SendGrid\Exception $e) {
            echo $e->getCode();
            foreach ($e->getErrors() as $er) {
                echo $er;
            }
        }
    }

}
