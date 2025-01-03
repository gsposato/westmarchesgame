<?php

namespace frontend\helpers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use common\models\Campaign;
use common\models\CampaignPlayer;

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
        if (empty($campaignId)) {
            $campaignId = $_GET['campaignId'];
        }
        $campaign = Campaign::findOne($campaignId);
        if (!$campaign) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $userId = Yii::$app->user->identity->id ?? 1;
        if ($userId == $campaign->owner) {
            return true;
        }
        if ($userId == $campaign->creator) {
            return true;
        }
        $campaignPlayers = CampaignPlayer::find()
            ->where(["campaignId" => $campaignId])
            ->andWhere(["userId" => $userId])
            ->all();
        if (!empty($campaignPlayers)) {
            return true;
        }
        throw new ForbiddenHttpException('This account is not authorized to view the requested page.');
    }

    /**
     * Get Player Rank
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
            if ($player->{$rank}) {
                return $rank;
            }
        }
        return '';
    }

}
