<?php

namespace frontend\controllers;

use Yii;
use common\models\Campaign;
use common\models\CampaignPlayer;
use common\models\Ticket;
use common\models\TicketComment;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\helpers\ControllerHelper;

/**
 * TicketController implements the CRUD actions for Ticket model.
 */
class TicketController extends Controller
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
     * Lists all Ticket models.
     *
     * @return string
     */
    public function actionIndex($campaignId)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }
        $query = Ticket::find()
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
     * Displays a single Ticket model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $campaignId)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }
        $comments = TicketComment::find()
            ->where(["ticketId" => $id])
            ->orderBy(["id" => SORT_DESC])
            ->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'comments' => $comments
        ]);
    }

    /**
     * Creates a new Ticket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($campaignId)
    {
        $model = new Ticket();
        $isPost = $this->request->isPost;
        if (!$isPost) {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
        $isHuman = $this->isHuman();
        if (!$isHuman) {
            $msg = "Failed to Create Ticket. Please try again.";
            Yii::$app->getSession()->setFlash('danger', $msg);
            $model->loadDefaultValues();
            return $this->render('create', [
                'model' => $model,
            ]);
        }
        $isLoaded = $model->load($this->request->post());
        if (empty($model->status)) {
            $model->status = Ticket::STATUS_NEW;
        }
        $isSaved = $model->save();
        if ($isLoaded && $isSaved) {
            $msg = "Successfully Created Ticket #".$model->id;
            Yii::$app->getSession()->setFlash('success', $msg);
            $this->sendSubscriberEmail($model);
            $model = new Ticket();
            $model->loadDefaultValues();
            return $this->render('create', [
                'model' => $model,
            ]);
        }
        $model->loadDefaultValues();
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Ticket model.
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
            return $this->redirect(['index', 'campaignId' => $campaignId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Ticket model.
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
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Ticket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ticket::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * is Human
     */
    protected function isHuman()
    {
        if (!Yii::$app->user->isGuest) {
            return true;
        }
        if (empty($_GET['campaignId'])) {
            return false;
        }
        $campaignId = $_GET['campaignId'];
        if (empty($_POST['challenge'])) {
            return false;
        }
        $challenge = $_POST['challenge'];
        $campaign = Campaign::findOne($campaignId);
        if (empty($campaign)) {
            return false;
        }
        $challenge = trim(strtolower($challenge));
        $answer = trim(strtolower($campaign->name));
        return str_contains($challenge, $answer);
    }

    /**
     * Send Subscriber Email
     * @param Object $model
     */
    protected function sendSubscriberEmail($model)
    {
        $now = time();
        $subscribers = CampaignPlayer::find()
            ->where(["campaignId" => $model->campaignId])
            ->andWhere(["isSubscribed" => 1])
            ->all();
        if (empty($subscribers)) {
            return;
        }
        foreach ($subscribers as $subscriber) {
        if (empty($subscriber->userId)) {
            continue;
        }
        $user = User::findOne($subscriber->userId);
        if (empty($user->email)) {
            continue;
        }
        $campaign = Campaign::findOne($model->campaignId);
        $campaign = $campaign->name ?? $model->campaignId;
        $unsubscribe = $this->unsubscribe($subscriber->id, $model->campaignId, $now);
        try {
            $result = Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'newTicket-html', 'text' => 'newTicket-text'],
                    ['campaign' => $campaign, 'unsubscribe' => $unsubscribe]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                ->setTo($user->email)
                ->setSubject('Ticket created at ' . Yii::$app->name)
                ->send();
            } catch (\Exception $e) {
                $result = $e->getMessage();
            }
            $note = "The following happened while trying to send an email to " . $subscriber->name . ": ";
            $note .= print_r($result, $return = true);
            if ($result === true) {
                $note = $subscriber->name . " was sent an email notification.";
            }
            $comment = new TicketComment();
            $comment->note = $note;
            $comment->campaignId = $model->campaignId;
            $comment->ticketId = $model->id;
            $comment->save();
        }
    }

    /**
     * Unsubscribe
     * @param integer $subscriberId
     * @param integer $campaignId
     * @param integer $now
     */
    protected function unsubscribe($subscriberId, $campaignId, $now)
    {
        $id = $_GET['campaignId'];
        $now = time();
        $arr["campaignId"] = $campaignId;
        $arr["campaignPlayerId"] = $subscriberId;
        $arr["unixtimestamp"] = $now;
        $json = json_encode($arr);
        $token = base64_encode($json);
        $unsubscribe = (empty($_SERVER['HTTPS']) ? 'http' : 'https');
        $unsubscribe .= "://$_SERVER[HTTP_HOST]/frontend/web/site/unsubscribe";
        $url = $unsubscribe . "?token=" . $token;
        return $url;
    }
}
