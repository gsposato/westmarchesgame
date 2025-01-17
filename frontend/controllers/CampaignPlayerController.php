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
    public function actionIndex($campaignId)
    {
        $query = CampaignPlayer::find()
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
     * Updates an existing CampaignPlayer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $campaignId)
    {
        $model = $this->findModel($id);

        $userId = Yii::$app->user->identity->id ?? 1;
        if ($userId == $model->userId) {
            $forbiddenException = 'This account is not authorized to view the requested page.';
            ControllerHelper::updateUserAction(403);
            throw new ForbiddenHttpException($forbiddenException);
        }
        if (!empty($_POST['CampaignPlayer']['gameEventTimestamp'])) {
            $model->gameEventTimestamp = strtotime($_POST['CampaignPlayer']['gameEventTimestamp']);
        }
        if (!empty($_POST['CampaignPlayer']['gameEventNumber'])) {
            $model->gameEventNumber = $_POST['CampaignPlayer']['gameEventNumber'];
        }
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index', 'campaignId' => $campaignId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
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
        if ($userId == $model->userId) {
            ControllerHelper::updateUserAction(403);
            $forbiddenException = 'This account is not authorized to view the requested page.';
            throw new ForbiddenHttpException($forbiddenException);
        }

        $this->findModel($id)->delete();

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
