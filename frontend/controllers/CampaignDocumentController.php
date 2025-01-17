<?php

namespace frontend\controllers;

use common\models\CampaignDocument;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use frontend\helpers\ControllerHelper;

/**
 * CampaignDocumentController implements the CRUD actions for CampaignDocument model.
 */
class CampaignDocumentController extends Controller
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
     * Lists all CampaignDocument models.
     *
     * @return string
     */
    public function actionIndex($campaignId)
    {
        $query = $this->query($campaignId);
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
     * Displays a single CampaignDocument model.
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
     * Creates a new CampaignDocument model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($campaignId)
    {
        $model = new CampaignDocument();

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
     * Updates an existing CampaignDocument model.
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
     * Deletes an existing CampaignDocument model.
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
     * Finds the CampaignDocument model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CampaignDocument the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException if the model cannot be accessed
     */
    protected function findModel($id)
    {
        $campaignId = $_GET['campaignId'];
        $forbiddenException = 'This account is not authorized to view the requested page.'; 
        if (($model = CampaignDocument::findOne(['id' => $id])) !== null) {
            switch (ControllerHelper::getPlayerRank($campaignId)) {
                case 'isAdmin':
                    return $model;
                    break;
                case 'isHost':
                    if ($model->hostVisible) {
                        return $model;
                    }
                    ControllerHelper::updateUserAction(403);
                    throw new ForbiddenHttpException($forbiddenException);
                    break;
                case 'isPlayer':
                    if ($model->playerVisible) {
                        return $model;
                    }
                    ControllerHelper::updateUserAction(403);
                    throw new ForbiddenHttpException($forbiddenException);
                    break;
                default:
                    ControllerHelper::updateUserAction(403);
                    throw new ForbiddenHttpException($forbiddenException);
            }
        }
        ControllerHelper::updateUserAction(404);
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Query
     * @param Integer $campaignId
     */
    protected function query($campaignId)
    {
        switch (ControllerHelper::getPlayerRank($campaignId)) {
            case 'isPlayer':
                $query = CampaignDocument::find()
                    ->where(["campaignId" => $campaignId])
                    ->andWhere(["PlayerVisible" => 1]);
                break;
            case 'isHost':
                $query = CampaignDocument::find()
                    ->where(["campaignId" => $campaignId])
                    ->andWhere(["HostVisible" => 1]);
                break;
            case 'isAdmin':
                $query = CampaignDocument::find()
                    ->where(["campaignId" => $campaignId]);
                break;
            default:
                ControllerHelper::updateUserAction(403);
                $forbiddenException = 'This account is not authorized to view the requested page.'; 
                throw new ForbiddenHttpException($forbiddenException);
        }
        return $query;
    }
}
