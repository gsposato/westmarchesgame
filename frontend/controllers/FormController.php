<?php

namespace frontend\controllers;

use Yii;
use common\models\Form;
use common\models\Ticket;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\helpers\ControllerHelper;

/**
 * FormController implements the CRUD actions for Form model.
 */
class FormController extends Controller
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
     * Lists all Form models.
     *
     * @return string
     */
    public function actionIndex($campaignId)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }
        $query = Form::find()
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
     * Displays a single Form model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $campaignId)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single Form model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionForm($id, $campaignId)
    {
        $ticket = new Ticket();
        $form = $this->findModel($id);
        $isPost = $this->request->isPost;
        if (!$isPost) {
            return $this->render('form', [
                'model' => $form,
            ]);
        }
        $isHuman = ControllerHelper::isHuman();
        if (!$isHuman) {
            $msg = "Failed to Submit Form. Please try again.";
            Yii::$app->getSession()->setFlash('danger', $msg);
            return $this->render('form', [
                'model' => $form,
            ]);
        }
        $arr = $_POST;
        unset($arr["_csrf-frontend"]);
        $ticket->note = json_encode($arr);
        $ticket->name = $form->name . " form submission";
        $isLoaded = !empty($ticket->note);
        if (empty($ticket->status)) {
            $ticket->status = Ticket::STATUS_NEW;
        }
        $isSaved = $ticket->save();
        if ($isLoaded && $isSaved) {
            $msg = "Successfully Created Ticket #".$ticket->id;
            Yii::$app->getSession()->setFlash('success', $msg);
            ControllerHelper::sendSubscriberEmail($ticket);
        }
        if (!$isSaved) {
            foreach ($ticket->getErrors() as $err) {
                print_r($err);
            }
        }
        return $this->render('form', [
            'model' => $form,
        ]);
    }

    /**
     * Creates a new Form model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($campaignId)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }
        $model = new Form();
        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->status = 1;
            if ($model->save()) {
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
     * Updates an existing Form model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $campaignId)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }
        $model = $this->findModel($id);
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view?campaignId='.$campaignId.'&id='.$model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Form model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $campaignId)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }
        $this->findModel($id)->delete();
        return $this->redirect(['index?campaignId='.$campaignId]);
    }

    /**
     * Finds the Form model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Form the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Form::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
