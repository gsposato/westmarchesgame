<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Email;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * Signs user up.
     *
     * @return string
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->save();
        return $this->sendEmail($user);
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return string
     */
    protected function sendEmail($user)
    {
        // prepare email
        $html = 'emailVerify-html';
        $text = 'emailVerify-text';
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
        $subject = 'Account registration at ';
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
            'site/verify-email',
            'token' => $user->verification_token
        ]);

        return $url;
    }
}
