<?php

namespace frontend\helpers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use common\models\Campaign;
use common\models\CampaignPlayer;
use common\models\TicketComment;
use common\models\UserAction;
use common\models\User;

class ControllerHelper
{
    /**
     * Default Controller Behaviors
     */
    public static function behaviors($canGuestView = false)
    {
        $roles = ['@'];
        if ($canGuestView) {
            $roles = ['@', '?'];
        }
        return [
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['index', 'view', 'create', 'update', 'delete'],
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'create', 'update', 'delete'],
                            'allow' => true,
                            'roles' => $roles,
                        ]
                    ]
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ];
    }

    /**
     * Can View
     * @param integer $campaignId
     */
    public static function canView($campaignId=0)
    {
        date_default_timezone_set("UTC");
        if (!empty(Yii::$app->user->identity->timezone)) {
            date_default_timezone_set(Yii::$app->user->identity->timezone);
        }
        if (empty($campaignId)) {
            $campaignId = $_GET['campaignId'];
        }
        $campaign = Campaign::findOne($campaignId);
        if (!$campaign) {
            self::createUserAction(404);
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $userId = Yii::$app->user->identity->id ?? 1;
        if ($userId == $campaign->owner) {
            self::createUserAction(200);
            return true;
        }
        if ($userId == $campaign->creator) {
            self::createUserAction(200);
            return true;
        }
        $uri = $_SERVER['REQUEST_URI'];
        $campaign = Campaign::findOne($campaignId);
        $campaignRules = (object) json_decode($campaign->rules);
        $uris = $campaignRules->Navigation ?? (object) Yii::$app->params['navigation'];
        $rank = self::getPlayerRank($campaignId);
        $canNavigate = false;
        if (!empty($uris->{$rank})) {
            foreach ($uris->{$rank} as $key => $value) {
                if (str_contains($uri, $value)) {
                    $canNavigate = true;
                    break;
                }
            }
        }
        $campaignPlayers = CampaignPlayer::find()
            ->where(["campaignId" => $campaignId])
            ->andWhere(["userId" => $userId])
            ->all();
        if (!empty($campaignPlayers) && $canNavigate) {
            self::createUserAction(200);
            return true;
        }
        self::createUserAction(403);
        throw new ForbiddenHttpException('This account is not authorized to view the requested page.');
    }

    /**
     * Get Player Rank
     * @param Integer $campaignId
     */
    public static function getPlayerRank($campaignId)
    {
        $userId = Yii::$app->user->identity->id ?? 1;
        $player = CampaignPlayer::find()
            ->where(["campaignId" => $campaignId])
            ->andWhere(["userId" => $userId])
            ->one();
        if (empty($player)) {
            return '';
        }
        $ranks = [
            'isAdmin',
            'isHost',
            'isPlayer'
        ];
        foreach ($ranks as $rank) {
            if ($player->{$rank}) {
                return $rank;
            }
        }
        return '';
    }

    /**
     * Is Support Role
     * @param integer $campaignId
     */
    public static function isSupportRole($campaignId)
    {
        $userId = Yii::$app->user->identity->id ?? 1;
        $player = CampaignPlayer::find()
            ->where(["campaignId" => $campaignId])
            ->andWhere(["userId" => $userId])
            ->one();
        if (empty($player->isSupport)) {
            return false;
        }
        return true;
    }

    /**
     * Create User Action
     * @param integer $statuscode
     */
    public static function createUserAction($statuscode)
    {
        $action = new UserAction();
        $action->userId = Yii::$app->user->identity->id ?? -1;
        $action->uri = $_SERVER['REQUEST_URI'];
        $action->unixtime = time();
        $action->statuscode = $statuscode;
        $action->save();
    }

    /**
     * Update User Action
     * @param integer $statuscode
     */
    public static function updateUserAction($statuscode)
    {
        $uri = $_SERVER['REQUEST_URI'];
        $userId = Yii::$app->user->identity->id;
        $action = UserAction::find()
            ->where(["userId" => $userId])
            ->andWhere(["uri" => $uri])
            ->orderBy(["id" => SORT_DESC])
            ->one();
        if (empty($action)) {
            return;
        }
        $action->statuscode = $statuscode;
        $action->save();
    }

    /**
     * Get URL
     */
    public static function url()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $domain = $_SERVER['HTTP_HOST'];
        return $protocol . $domain;
    }

    /**
     * is Human
     */
    public static function isHuman()
    {
        if (!Yii::$app->user->isGuest) {
            return true;
        }
        if (empty($_GET['campaignId'])) {
            return false;
        }
        $campaignId = $_GET['campaignId'];
        if (empty($_POST['challenge'])) {
            return false;
        }
        $challenge = $_POST['challenge'];
        $campaign = Campaign::findOne($campaignId);
        if (empty($campaign)) {
            return false;
        }
        $challenge = trim(strtolower($challenge));
        $answer = trim(strtolower($campaign->name));
        return str_contains($challenge, $answer);
    }

    /**
     * Send Subscriber Email
     * @param Object $model
     */
    public static function sendSubscriberEmail($model)
    {
        $now = time();
        $subscribers = CampaignPlayer::find()
            ->where(["campaignId" => $model->campaignId])
            ->andWhere(["isSubscribed" => 1])
            ->all();
        if (empty($subscribers)) {
            return;
        }
        foreach ($subscribers as $subscriber) {
        if (empty($subscriber->userId)) {
            continue;
        }
        $user = User::findOne($subscriber->userId);
        if (empty($user->email)) {
            continue;
        }
        $campaign = Campaign::findOne($model->campaignId);
        $campaign = $campaign->name ?? $model->campaignId;
        $unsubscribe = self::unsubscribe($subscriber->id, $model->campaignId, $now);
        try {
            $result = Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'newTicket-html', 'text' => 'newTicket-text'],
                    ['campaign' => $campaign, 'unsubscribe' => $unsubscribe]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                ->setTo($user->email)
                ->setSubject('Ticket created at ' . Yii::$app->name)
                ->send();
            } catch (\Exception $e) {
                $result = $e->getMessage();
            }
            $note = "The following happened while trying to send an email to " . $subscriber->name . ": ";
            $note .= print_r($result, $return = true);
            if ($result === true) {
                $note = $subscriber->name . " was sent an email notification.";
            }
            $comment = new TicketComment();
            $comment->note = $note;
            $comment->campaignId = $model->campaignId;
            $comment->ticketId = $model->id;
            $comment->owner = $model->owner;
            $comment->creator = $model->creator;
            $comment->created = $model->created;
            $comment->updated = $model->updated;
            $comment->save();
        }
    }

    /**
     * Unsubscribe
     * @param integer $subscriberId
     * @param integer $campaignId
     * @param integer $now
     */
    public static function unsubscribe($subscriberId, $campaignId, $now)
    {
        $id = $_GET['campaignId'];
        $now = time();
        $arr["campaignId"] = $campaignId;
        $arr["campaignPlayerId"] = $subscriberId;
        $arr["unixtimestamp"] = $now;
        $json = json_encode($arr);
        $token = base64_encode($json);
        $unsubscribe = (empty($_SERVER['HTTPS']) ? 'http' : 'https');
        $unsubscribe .= "://$_SERVER[HTTP_HOST]/frontend/web/site/unsubscribe";
        $url = $unsubscribe . "?token=" . $token;
        return $url;
    }
}
