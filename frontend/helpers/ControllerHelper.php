<?php

namespace frontend\helpers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use common\models\Campaign;
use common\models\CampaignPlayer;
use common\models\UserAction;

class ControllerHelper
{
    /**
     * Default Controller Behaviors
     */
    public static function behaviors()
    {
        return [
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['index', 'view', 'create', 'update', 'delete'],
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'create', 'update', 'delete'],
                            'allow' => true,
                            'roles' => ['@'],
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
        $campaignPlayers = CampaignPlayer::find()
            ->where(["campaignId" => $campaignId])
            ->andWhere(["userId" => $userId])
            ->all();
        if (!empty($campaignPlayers)) {
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
        $ranks = [
            'isAdmin',
            'isHost',
            'isPlayer'
        ];
        foreach ($ranks as $rank) {
            if (empty($player->{$rank})) {
                return '';
            }
            if ($player->{$rank}) {
                return $rank;
            }
        }
        return '';
    }

    /**
     * Create User Action
     * @param integer $statuscode
     */
    public static function createUserAction($statuscode)
    {
        $action = new UserAction();
        $action->userId = Yii::$app->user->identity->id;
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

}
