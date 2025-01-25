<?php

namespace frontend\controllers;

use common\models\Game;
use common\models\GameNote;
use common\models\GamePoll;
use common\models\GamePollSlot;
use common\models\GameEvent;
use common\models\GamePlayer;
use common\models\CampaignCharacter;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\helpers\ControllerHelper;

/**
 * GameController implements the CRUD actions for Game model.
 */
class GameController extends Controller
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
     * Lists all Game models.
     *
     * @return string
     */
    public function actionIndex($campaignId)
    {
        $query = Game::find()
            ->where(["campaignId" => $campaignId]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Roundup
     *
     * @return string
     */
    public function actionRoundup($campaignId, $after=0, $before=0)
    {
        $now = time();
        $after = strtotime($after);
        if (empty($after)) {
            $after = $now - (30 * 24 * 60 * 60);
        }
        $before = strtotime($before);
        if (empty($before)) {
            $before = $now;
        }
        $games = array();
        $allGames = Game::find()
            ->where(["campaignId" => $campaignId])
            ->all();
        foreach ($allGames as $game) {
            $gameEvent = GameEvent::find()
                ->where(["gameId" => $game->id])
                ->one();
            if (empty($gameEvent->gamePollSlotId)) {
                continue;
            }
            $gamePollSlot = GamePollSlot::findOne($gameEvent->gamePollSlotId);
            if (empty($gamePollSlot)) {
                continue;
            }
            if ($gamePollSlot->unixtime <= $after) {
                continue;
            }
            if ($gamePollSlot->unixtime >= $before) {
                continue;
            }
            array_push($games, $game);
        }
        $levels = CampaignCharacter::levels($campaignId);
        $retired = CampaignCharacter::find()
            ->where([">=", "updated", $after])
            ->andWhere(["status" => 3])
            ->all();
        $new = CampaignCharacter::find()
            ->where([">=", "created", $after])
            ->andWhere(["status" => CampaignCharacter::STATUS_ACTIVE])
            ->all();
        return $this->render('roundup', [
            'games' => $games,
            'levels' => $levels,
            'retired' => $retired,
            'new' => $new,
        ]);
    }

    /**
     * Displays a single Game model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $campaignId)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Game model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($campaignId)
    {
        $model = new Game();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index', 'campaignId' => $campaignId]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Game model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $campaignId)
    {
        $model = $this->findModel($id);

        if (!empty($_POST["GamePoll"]["note"])) {
            $gamePoll = GamePoll::find()->where(["gameId" => $id])->one();
            if (!empty($gamePoll)) {
                $gamePoll->note = $_POST["GamePoll"]["note"];
                $gamePoll->save();
            }
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $url = 'view?campaignId='.$campaignId.'&id='.$id;
            return $this->redirect([$url]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Game model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $campaignId)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index', 'campaignId' => $campaignId]);
    }

    /**
     * Game Poll
     * @param Integer $id
     * @param Integer $campaignId
     */
    public function actionPoll($id, $campaignId)
    {
        $url = 'view?campaignId='. $campaignId.'&id='.$id.'#gamepoll';
        $poll = GamePoll::find()->where(["gameId" => $id])->one();
        if (!empty($poll)) {
            return $this->redirect([$url]);
        }
        $poll = new GamePoll();
        $poll->load($this->request->post());
        $poll->gameId = $id;
        $poll->save();
        return $this->redirect([$url]);
    }

    /**
     * Game Poll Slot
     * @param Integer $id
     * @param Integer $campaignId
     */
    public function actionPollslot($id, $campaignId)
    {
        $url = 'view?campaignId='. $campaignId.'&id='.$id.'#gamepoll';
        $poll = GamePoll::find()->where(["gameId" => $id])->one();
        if (empty($poll)) {
            return $this->redirect([$url]);
        }
        $slot = new GamePollSlot();
        $slot->load($this->request->post());
        $slot->unixtime = strtotime($slot->humantime);
        $slot->gamePollId = $poll->id;
        $slot->save();
        return $this->redirect([$url]);
    }

    /**
     * Game Poll Slot Delete
     * @param Integer $id
     * @param Integer $campaignId
     * @param Integer $slotId
     */
    public function actionPollslotdelete($id, $campaignId, $slotId)
    {
        $url = 'view?campaignId='. $campaignId.'&id='.$id.'#gamepoll';
        $event = GameEvent::find()->where(["gamePollSlotId" => $slotId])->one();
        if (!empty($event)) {
            return $this->redirect([$url]);
        }
        $poll = GamePoll::find()->where(["gameId" => $id])->one();
        if (empty($poll)) {
            return $this->redirect([$url]);
        }
        $slot = GamePollSlot::findOne($slotId);
        if (empty($slot)) {
            return $this->redirect([$url]);
        }
        if ($slot->gamePollId != $poll->id) {
            return $this->redirect([$url]);
        }
        $slot->delete();
        return $this->redirect([$url]);
    }

    /**
     * Game Event
     * @param Integer $id
     * @param Integer $campaignId
     * @param Integer $slotId
     */
    public function actionEvent($id, $campaignId, $slotId)
    {
        $url = 'view?campaignId='. $campaignId.'&id='.$id.'#gameevent';
        $event = GameEvent::find()->where(["gameId" => $id])->one();
        if (!empty($event)) {
            return $this->redirect([$url]);
        }
        $event = new GameEvent();
        $event->load($this->request->post());
        $event->gameId = $id;
        $event->note = uniqId();
        $event->gamePollSlotId = $slotId;
        $event->save();
        return $this->redirect([$url]);
    }

    /**
     * Game Player
     * @param Integer $id
     * @param Integer $campaignId
     */
    public function actionPlayer($id, $campaignId)
    {
        $this->addOwnerToGame($id);
        $url = 'view?campaignId='. $campaignId.'&id='.$id.'#gameevent';
        $player = new GamePlayer();
        $player->load($this->request->post());
        $exists = GamePlayer::find()
            ->where(["userId" => $player->userId])
            ->andWhere(["gameId" => $id])
            ->one();
        if (!empty($exists)) {
            return $this->redirect([$url]);
        }
        $player->gameId = $id;
        $player->characterId = -1; // fill in later
        $player->status = GamePlayer::STATUS_SCHEDULED;
        $player->save();
        return $this->redirect([$url]);
    }

    /**
     * Game Player Status
     * @param Integer $id
     * @param Integer $campaignId
     * @param Integer $gamePlayerId
     */
    public function actionPlayerstatus($id, $campaignId, $gamePlayerId)
    {
        $url = 'view?campaignId='. $campaignId.'&id='.$id.'#gameevent';
        $player = GamePlayer::findOne($gamePlayerId);
        if (empty($player)) {
            return $this->redirect([$url]);
        }
        $player->status = GamePlayer::change($player->status);
        $player->save();
        return $this->redirect([$url]);
    }

    /**
     * Game Character
     * @param Integer $id
     * @param Integer $campaignId
     * @param Integer $characterId
     */
    public function actionCharacter($id, $campaignId, $characterId)
    {
        $url = 'view?campaignId='. $campaignId.'&id='.$id.'#gamecharacter';
        $character = CampaignCharacter::findOne($characterId);
        if (empty($character)) {
            return $this->redirect([$url]);
        }
        $player = GamePlayer::find()
            ->where(["userId" => $character->playerId])
            ->andWhere(["gameId" => $id])
            ->one();
        if (empty($player)) {
            return $this->redirect([$url]);
        }
        if ($player->characterId == $character->id) {
            $player->characterId = -1;
            $player->save();
            return $this->redirect([$url]);
        }
        $player->characterId = $character->id;
        $player->save();
        return $this->redirect([$url]);
    }

    /**
     * Game Note
     * @param Integer $id
     * @param Integer $campaignId
     */
    public function actionNote($id, $campaignId)
    {
        $url = 'view?campaignId='. $campaignId.'&id='.$id.'#gamesummary';
        if (!$this->request->isPost) {
            return $this->redirect([$url]);
        }
        $model = new GameNote();
        $model->load($this->request->post());
        $model->gameId = $id;
        $model->pinned = 0;
        $model->save();
        return $this->redirect([$url]);
    }

    /**
     * Delete Game Notes
     * @param Integer $id
     * @param Integer $campaignId
     */
    public function actionDeletenotes($id, $campaignId)
    {
        $url = 'view?campaignId='. $campaignId.'&id='.$id.'#gamesummary';
        $notes = GameNote::find()->where(["gameId" => $id])->all();
        if (empty($notes)) {
            return $this->redirect([$url]);
        }
        foreach ($notes as $note) {
            $note->delete();
        }
        return $this->redirect([$url]);
    }

    /**
     * Game Bonus
     * @param Integer $id
     * @param Integer $campaignId
     * @param Integer $characterId
     */
    public function actionBonus($id, $campaignId, $characterId)
    {
        $url = 'view?campaignId='. $campaignId.'&id='.$id.'#gamebonus';
        $character = CampaignCharacter::findOne($characterId);
        if (empty($character)) {
            return $this->redirect([$url]);
        }
        $player = GamePlayer::find()
            ->where(["userId" => $character->playerId])
            ->andWhere(["characterId" => $character->id])
            ->andWhere(["gameId" => $id])
            ->one();
        if (empty($player)) {
            return $this->redirect([$url]);
        }
        $isHost = CampaignCharacter::isHostCharacter($id, $characterId);
        $player->bonus($isHost);
        return $this->redirect([$url]);
    }

    /**
     * Add Owner To Game
     * @param Integer $id
     */
    protected function addOwnerToGame($id)
    {
        $game = Game::findOne($id);
        if (empty($game)) {
            return;
        }
        $player = new GamePlayer();
        $exists = GamePlayer::find()
            ->where(["userId" => $game->owner])
            ->andWhere(["gameId" => $id])
            ->one();
        if (!empty($exists)) {
            return;
        }
        $player->gameId = $id;
        $player->characterId = -1;
        $player->userId = $game->owner;
        $player->status = GamePlayer::STATUS_SCHEDULED;
        $player->save();
    }

    /**
     * Finds the Game model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Game the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Game::findOne(['id' => $id])) !== null) {
            return $model;
        }
        ControllerHelper::updateUserAction(404);
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
