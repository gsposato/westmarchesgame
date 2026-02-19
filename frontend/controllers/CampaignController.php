<?php

namespace frontend\controllers;

use Yii;
use common\models\Campaign;
use common\models\CampaignPlayer;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use frontend\helpers\ControllerHelper;

/**
 * CampaignController implements the CRUD actions for Campaign model.
 */
class CampaignController extends Controller
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
     * Lists all Campaign models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->redirect('/');
    }

    /**
     * Displays a single Campaign model.
     * @param int $campaignId ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($campaignId=0, $id=0)
    {
        if (empty($campaignId) && !empty($id)) {
            $campaignId = $id;
        }
        return $this->render('view', [
            'model' => $this->findModel($campaignId),
        ]);
    }

    /**
     * Creates a new Campaign model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Campaign();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $cp = new CampaignPlayer();
                $cp->campaignId = $model->id;
                $cp->name = Yii::$app->user->identity->username;
                $cp->userId = Yii::$app->user->identity->id;
                $cp->isPlayer = 1;
                $cp->isHost = 1;
                $cp->isAdmin = 1;
                $cp->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Campaign model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($campaignId)
    {
        $model = $this->findModel($campaignId);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'campaignId' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Campaign model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($campaignId)
    {
        $this->findModel($campaignId)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Campaign model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Campaign the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Campaign::findOne(['id' => $id])) !== null) {
            return $model;
        }
        ControllerHelper::updateUserAction(404);
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
