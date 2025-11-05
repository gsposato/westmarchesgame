<?php

namespace frontend\controllers;

use Yii;
use common\models\CampaignPlayer;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use frontend\helpers\ControllerHelper;

/**
 * CampaignPlayerController implements the CRUD actions for CampaignPlayer model.
 */
class CampaignPlayerController extends Controller
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
     * Lists all CampaignPlayer models.
     *
     * @return string
     */
    public function actionIndex($campaignId, $hibernated = 0)
    {
        $query = CampaignPlayer::find()
            ->where(["campaignId" => $campaignId])
            ->andWhere(["hibernated" => $hibernated])
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
     * Displays a single CampaignPlayer model.
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
     * Creates a new CampaignPlayer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($campaignId)
    {
        $model = new CampaignPlayer();
        $rank = ControllerHelper::getPlayerRank($campaignId);
        $campaignPlayers = CampaignPlayer::find()->where(["campaignId" => $campaignId])->all();
        $canCreate = ($rank == 'isAdmin' || empty($campaignPlayers));
        $isPost = $this->request->isPost;
        if (!$isPost) {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
        $isLoaded = false;
        $isSaved = false;
        if ($canCreate) {
            $isLoaded = $model->load($this->request->post());
            $isSaved = $model->save();
        }
        if ($isLoaded && $isSaved) {
            return $this->redirect(['index', 'campaignId' => $campaignId]);
        }
        if (!$canCreate) {
            Yii::$app->session->setFlash("danger", "An administrator is required to perform that action.");
        }
        $model->loadDefaultValues();
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CampaignPlayer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $campaignId)
    {
        $model = $this->findModel($id);
        $rank = ControllerHelper::getPlayerRank($campaignId);
        $userId = Yii::$app->user->identity->id ?? 1;
        if (!empty($_POST['CampaignPlayer']['gameEventTimestamp'])) {
            $model->gameEventTimestamp = strtotime($_POST['CampaignPlayer']['gameEventTimestamp']);
        }
        if (!empty($_POST['CampaignPlayer']['gameEventNumber'])) {
            $model->gameEventNumber = $_POST['CampaignPlayer']['gameEventNumber'];
        }
        if ($this->request->isPost && $userId == $model->userId && $rank != 'isAdmin') {
            $model->isSubscribed = $_POST['CampaignPlayer']['isSubscribed'];
            $model->save();
            return $this->redirect(['index', 'campaignId' => $campaignId]);
        }
        if ($rank == 'isAdmin') {
            if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index', 'campaignId' => $campaignId]);
            }
        }
        if ($userId == $model->userId && $rank != 'isAdmin') {
            return $this->render('update-self', [
                'model' => $model,
            ]);
        }
        if ($this->request->isPost) {
            Yii::$app->session->setFlash("danger", "An administrator is required to perform that action.");
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Hibernate Campaign Player
     * @param integer $campaignId
     * @param integer $id
     */
    public function actionHibernate($campaignId, $id)
    {
        $userId = Yii::$app->user->identity->id ?? 1;
        $model = $this->findModel($id);
        if ($userId == $model->userId) {
            ControllerHelper::updateUserAction(403);
            $forbiddenException = 'This account is not authorized to view the requested page.';
            throw new ForbiddenHttpException($forbiddenException);
        }
        $rank = ControllerHelper::getPlayerRank($campaignId);
        if ($rank == 'isAdmin') {
            $model->hibernated = 1;
            $model->save();
        } else {
            Yii::$app->session->setFlash("danger", "An administrator is required to perform that action.");
        }
        return $this->redirect(['index', 'campaignId' => $campaignId]);
    }

    /**
     * Activate Campaign Player
     * @param integer $campaignId
     * @param integer $id
     */
    public function actionActivate($campaignId, $id)
    {
        $userId = Yii::$app->user->identity->id ?? 1;
        $model = $this->findModel($id);
        if ($userId == $model->userId) {
            ControllerHelper::updateUserAction(403);
            $forbiddenException = 'This account is not authorized to view the requested page.';
            throw new ForbiddenHttpException($forbiddenException);
        }
        $rank = ControllerHelper::getPlayerRank($campaignId);
        if ($rank == 'isAdmin') {
            $model->hibernated = 0;
            $model->save();
        } else {
            Yii::$app->session->setFlash("danger", "An administrator is required to perform that action.");
        }
        return $this->redirect(['index', 'campaignId' => $campaignId]);
    }

    /**
     * Deletes an existing CampaignPlayer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $campaignId)
    {
        $userId = Yii::$app->user->identity->id ?? 1;
        $model = $this->findModel($id);
        if ($userId == $model->userId) {
            ControllerHelper::updateUserAction(403);
            $forbiddenException = 'This account is not authorized to view the requested page.';
            throw new ForbiddenHttpException($forbiddenException);
        }
        $rank = ControllerHelper::getPlayerRank($campaignId);
        if ($rank == 'isAdmin') {
            $model->delete();
        } else {
            Yii::$app->session->setFlash("danger", "An administrator is required to perform that action.");
        }
        return $this->redirect(['index', 'campaignId' => $campaignId]);
    }

    /**
     * Finds the CampaignPlayer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CampaignPlayer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CampaignPlayer::findOne(['id' => $id])) !== null) {
            return $model;
        }
        ControllerHelper::updateUserAction(404);
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
