<?php

namespace frontend\controllers;

use common\models\EquipmentGoalRequirement;
use common\models\Event;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EquipmentGoalRequirementController implements the CRUD actions for EquipmentGoalRequirement model.
 */
class EquipmentGoalRequirementController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all EquipmentGoalRequirement models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => EquipmentGoalRequirement::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EquipmentGoalRequirement model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($campaignId, $id)
    {
        $events = Event::find()
            ->where(["modelClass" => "EquipmentGoalRequirement"])
            ->andWhere(["modelId" => $id])
            ->all();
        return $this->render('view', [
            'events' => $events,
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new EquipmentGoalRequirement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($campaignId, $equipmentGoalId)
    {
        $model = new EquipmentGoalRequirement();
        $model->equipmentGoalId = $equipmentGoalId;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['/equipment-goal/view?campaignId='.$campaignId.'&id='.$equipmentGoalId]);
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing EquipmentGoalRequirement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $campaignId)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view?campaignId='.$campaignId.'&id='.$model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EquipmentGoalRequirement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $campaignId)
    {
        $model = $this->findModel($id);
        $goalId = $model->equipmentGoalId;
        $model->delete();
        return $this->redirect(['/equipment-goal/view?campaignId='.$campaignId.'&id='.$goalId]);
    }

    /**
     * Finds the EquipmentGoalRequirement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return EquipmentGoalRequirement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EquipmentGoalRequirement::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
