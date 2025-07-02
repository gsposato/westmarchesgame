<?php

use common\models\CampaignPlayer;
use common\models\User;
use common\models\Ticket;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Tickets';
$campaignId = $_GET['campaignId'];
$create = 'create?campaignId=' . $campaignId;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Ticket', [$create], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'label' => 'Name',
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($model) {
                    $player = CampaignPlayer::findOne($model->name);
                    if (!empty($player->name)) {
                        return $player->name;
                    }
                    return $model->name;
                },
            ],
            [
                'label' => 'Status',
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model) {
                    $status = Ticket::status();
                    $style = Ticket::style();
                    $html = "<span class='badge badge-".$style[$model->status]."'>";
                    $html .= $status[$model->status];
                    $html.= "</span>";
                    return $html;
                },
            ],
            [
                'label' => 'Owner',
                'attribute' => 'owner',
                'format' => 'raw',
                'value' => function($model) {
                    $user = User::findOne($model->owner);
                    if (!empty($user->username)) {
                        return $user->username;
                    }
                    return $model->owner;
                },
            ],
            'updated:datetime',
            [
                'label' => '',
                'attribute' => '',
                'format' => 'raw',
                'value' => function($model) {
                    $link = "/frontend/web/ticket/view";
                    $link .= "?campaignId=" . $_GET['campaignId'];
                    $link .= "&id=" . $model->id; 
                    $html = '<a class="btn btn-primary" href="'.$link.'">Manage</a>';
                    return $html;
                },
            ],
        ],
    ]); ?>


</div>
