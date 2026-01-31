<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Email;
use common\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return string
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        $token = $user->password_reset_token;
        $isValidToken = User::isPasswordResetTokenValid($token);
        
        if (!$isValidToken) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        $html = 'passwordResetToken-html';
        $text = 'passwordResetToken-text';
        $templates = [
            'html' => $html,
            'text' => $text,
        ];
        $variables = [
            'user' => $user
        ];
        $fromEmail = Yii::$app->params['supportEmail'];
        $fromName = Yii::$app->name;
        $from = [
            $fromEmail => $fromName
        ];
        $to = $this->email;
        $subject = 'Password reset for ';
        $subject .= Yii::$app->name;

        $start = microtime(true);

        try {
            // send email
            $result = Yii::$app
                ->mailer
                ->compose($templates, $variables)
                ->setFrom($from)
                ->setTo($to)
                ->setSubject($subject)
                ->send();
        } catch (\Throwable $e) {
            // capture unusual result
            $result = $e->getMessage();
        }

        // record result
        $end = microtime(true);
        $now = time();
        $email = new Email();
        $email->name = uniqId();
        $email->result = strval($result) ?? "";
        $email->response = $end - $start;
        $email->owner = 0; // system
        $email->creator = 0; // system
        $email->created = $now;
        $email->updated = $now;
        $email->deleted = $now;
        $email->save();

        $url = Yii::$app->urlManager->createAbsoluteUrl([
            'site/reset-password',
            'token' => $token
        ]);

        return $url;
    }
}
