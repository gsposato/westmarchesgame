<?php

namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\CampaignPlayer;
use common\models\LoginForm;
use common\models\User;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\Campaign;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->actionLogin();
        }
        $campaigns = Campaign::getMyCampaigns();
        $params = [
            "campaigns" => $campaigns
        ];
        return $this->render('index', $params);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Sets User Timezone.
     * @param string $tz
     * @return mixed
     */
    public function actionTimezone($tz)
    {
        $isGuest = empty(Yii::$app->user->identity->id);
        if ($isGuest) {
            return;
        }
        $user = User::findOne(Yii::$app->user->identity->id);
        if (!$user) {
            return;
        }
        $timezone = urldecode($tz);
        $user->timezone = $timezone;
        if ($user->save()) {
            return 'timezone set to ' . $timezone;
        }
        foreach ($user->getErrors() as $err) {
            print_r($err);
        }
        return;
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup($token="")
    {
        if (empty($token)) {
            return $this->goHome();
        }
        $json = json_decode(base64_decode($token));
        $keys = [
            "campaignId",
            "campaignPlayerId",
            "unixtimestamp"
        ];
        foreach ($keys as $key) {
            if (empty($json->{$key})) {
                return $this->goHome();
            }
        }
        $twentyFourHoursAgo = time() - 86400;
        if ($json->unixtimestamp < $twentyFourHoursAgo) {
            return $this->goHome();
        }
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            $campaignPlayer = CampaignPlayer::findOne($json->campaignPlayerId);
            if (empty($campaignPlayer->userId)) {
                $user = User::find()
                    ->where(["status" => 9])
                    ->orderBy(["id" => SORT_DESC])
                    ->one();
                $campaignPlayer->userId = $user->id;
                $campaignPlayer->save();
            }
            Yii::$app->session->setFlash(
                'success',
                'Thank you for registration. Please check your inbox for verification email.'
            );
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }
            Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    /**
     * Unsubscribe
     * @param string $token
     */
    public function actionUnsubscribe($token="")
    {
        if (empty($token)) {
            return $this->goHome();
        }
        $json = json_decode(base64_decode($token));
        $keys = [
            "campaignId",
            "campaignPlayerId",
            "unixtimestamp"
        ];
        foreach ($keys as $key) {
            if (empty($json->{$key})) {
                return $this->goHome();
            }
        }
        $twentyFourHoursAgo = time() - 86400;
        if ($json->unixtimestamp < $twentyFourHoursAgo) {
            return $this->goHome();
        }
        $player = CampaignPlayer::find()
            ->where(["campaignid" => $json->campaignId])
            ->andWhere(["id" => $json->campaignPlayerId])
            ->one();
        if (!$player) {
            return false;
        }
        $player->isSubscribed = 0;
        $player->save();
        $campaign = Campaign::findOne($json->campaignId);
        $msg = "Successfully unsubscribed from campaign.";
        if (!empty($campaign->name)) {
            $msg = "Successfully unsubscribed from campaign <b>{$campaign->name}</b>.";
        }
        Yii::$app->session->setFlash('success', $msg);
        return $this->goHome();
    }
}
