<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\helpers\ControllerHelper;

/**
 * DeletedController implements the CRUD actions for NotarizedModel model.
 */
class DeletedController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            ControllerHelper::behaviors()
        );
    }

    /**
     * @inheritDoc
     */
    public function beforeAction($action)
    {
        ControllerHelper::canView();
        return parent::beforeAction($action);
    }

    /**
     * Lists all NotarizedModel models.
     *
     * @return string
     */
    public function actionIndex($campaignId)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }
        $sql = <<<SQL
SELECT 'campaign_announcement' AS tableName, id, name, deleted
FROM campaign_announcement
WHERE deleted <> 0
AND campaignId = :campaignId

UNION ALL

SELECT 'campaign_character' AS tableName, id, name, deleted
FROM campaign_character
WHERE deleted <> 0
AND campaignId = :campaignId

UNION ALL

SELECT 'campaign_document' AS tableName, id, name, deleted
FROM campaign_character
WHERE deleted <> 0
AND campaignId = :campaignId

UNION ALL

SELECT 'campaign_player' AS tableName, id, name, deleted
FROM campaign_player
WHERE deleted <> 0
AND campaignId = :campaignId

UNION ALL

SELECT 'currency' AS tableName, id, name, deleted
FROM currency
WHERE deleted <> 0
AND campaignId = :campaignId

UNION ALL

SELECT 'equipment' AS tableName, id, name, deleted
FROM equipment
WHERE deleted <> 0
AND campaignId = :campaignId

UNION ALL

SELECT 'form' AS tableName, id, name, deleted
FROM form
WHERE deleted <> 0
AND campaignId = :campaignId

UNION ALL

SELECT 'game' AS tableName, id, name, deleted
FROM game
WHERE deleted <> 0
AND campaignId = :campaignId

UNION ALL

SELECT 'map' AS tableName, id, name, deleted
FROM map
WHERE deleted <> 0
AND campaignId = :campaignId

UNION ALL

SELECT 'player_complaint' AS tableName, id, name, deleted
FROM player_complaint
WHERE deleted <> 0
AND campaignId = :campaignId

UNION ALL

SELECT 'player_trigger' AS tableName, id, name, deleted
FROM player_trigger
WHERE deleted <> 0
AND campaignId = :campaignId

UNION ALL

SELECT 'purchase' AS tableName, id, name, deleted
FROM purchase
WHERE deleted <> 0
AND campaignId = :campaignId

UNION ALL

SELECT 'ticket' AS tableName, id, name, deleted
FROM purchase
WHERE deleted <> 0
AND campaignId = :campaignId

ORDER BY deleted DESC

SQL;
    $deletedRecords = Yii::$app
        ->db
        ->createCommand($sql)
        ->bindValue(":campaignId", intval($campaignId))
        ->queryAll();
        return $this->render('index', [
            'deletedRecords' => $deletedRecords,
        ]);
    }

    /**
     * Restore a deleted record.
     * @param integer $campaignId
     * @param integer $recordId
     * @param string $tableName
     */
    public function actionRestore($campaignId, $recordId, $tableName)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }
        $rank = ControllerHelper::getPlayerRank($campaignId);
        if ($rank != "isAdmin") { 
            return $this->redirect(['/']);
        }
        switch ($tableName) {
            case "campaign_announcement":
            case "campaign_character":
            case "campaign_document":
            case "campaign_player":
            case "currency":
            case "equipment":
            case "form":
            case "game":
            case "map":
            case "player_complaint":
            case "player_trigger":
            case "purchase":
            case "ticket":
            break;
            default:
                return $this->redirect(['/']);
        }
        $sql = <<<SQL
UPDATE {$tableName}
SET deleted = 0
WHERE id = :recordId
AND campaignId = :campaignId
SQL;
        Yii::$app
            ->db
            ->createCommand($sql)
            ->bindValue(":recordId", $recordId)
            ->bindValue(":campaignId", $campaignId)
            ->execute();
        return $this->redirect(['/deleted/index?campaignId=' . $campaignId]);
    }
}
