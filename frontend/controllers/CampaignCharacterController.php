<?php

namespace frontend\controllers;

use Yii;
use common\models\Campaign;
use common\models\CampaignCharacter;
use common\models\CampaignPlayer;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\helpers\ControllerHelper;

/**
 * CampaignCharacterController implements the CRUD actions for CampaignCharacter model.
 */
class CampaignCharacterController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            ControllerHelper::behaviors($canGuestView = true)
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
     * Lists all CampaignCharacter models.
     *
     * @return string
     */
    public function actionIndex($campaignId)
    {
        $query = CampaignCharacter::find()
            ->where(["campaignId" => $campaignId])
            ->andWhere(["deleted" => 0]);
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
     * @param integer $campaignId
     * @return string
     */
    public function actionRoundup($campaignId)
    {
        $query = CampaignCharacter::find()
            ->where(["campaignId" => $campaignId])
            ->andWhere(["type" => 1])
            ->andWhere(["status" => CampaignCharacter::STATUS_ACTIVE]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 150
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $campaign = Campaign::findOne($campaignId);
        $campaignRules = json_decode($campaign->rules);
        $alerts = $campaignRules->CampaignCharacter->alerts ?? array();

        return $this->render('roundup', [
            'alerts' => $alerts,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CampaignCharacter model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $campaignId)
    {
        $model = $this->findModel($id);
        $player = CampaignPlayer::findOne($model->playerId);
        if (!empty($player->id) && !Yii::$app->user->isGuest) {
            if ($player->userId == Yii::$app->user->identity->id) {
                $model->owner = $player->userId;
                $model->save();
            }
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CampaignCharacter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($campaignId)
    {
        $model = new CampaignCharacter();

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
     * Updates an existing CampaignCharacter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $campaignId)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index', 'campaignId' => $campaignId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CampaignCharacter model.
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
     * Finds the CampaignCharacter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CampaignCharacter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CampaignCharacter::findOne(['id' => $id])) !== null) {
            return $model;
        }
        ControllerHelper::updateUserAction(404);
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
