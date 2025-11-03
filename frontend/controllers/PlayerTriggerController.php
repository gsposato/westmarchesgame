<?php

namespace frontend\controllers;

use common\models\PlayerTrigger;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\helpers\ControllerHelper;

/**
 * PlayerTriggerController implements the CRUD actions for PlayerTrigger model.
 */
class PlayerTriggerController extends Controller
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
     * Lists all PlayerTrigger models.
     *
     * @return string
     */
    public function actionIndex($campaignId)
    {
        $query = PlayerTrigger::find()
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
     * Displays a single PlayerTrigger model.
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
     * Creates a new PlayerTrigger model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($campaignId)
    {
        $model = new PlayerTrigger();

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
     * Updates an existing PlayerTrigger model.
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
     * Deletes an existing PlayerTrigger model.
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
     * Finds the PlayerTrigger model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return PlayerTrigger the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlayerTrigger::findOne(['id' => $id])) !== null) {
            return $model;
        }
        ControllerHelper::updateUserAction(404);
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
