<?php

namespace frontend\helpers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use common\models\Campaign;

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
    public static function canView($campaignId)
    {
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
        /**
         * @todo
         * add lookup to see if they are campaign player
         */
        throw new ForbiddenHttpException('This account is not authorized to view the requested page.');
    }

}
