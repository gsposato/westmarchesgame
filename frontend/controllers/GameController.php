<?php

namespace frontend\controllers;

use common\models\Game;
use common\models\GamePoll;
use common\models\GamePollSlot;
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

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
