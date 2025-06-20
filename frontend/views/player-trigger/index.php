<?php

use common\models\CampaignPlayer;
use common\models\PlayerTrigger;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Player Triggers';
$this->params['breadcrumbs'][] = $this->title;
$campaignId = $_GET['campaignId'];
$create = 'create?campaignId=' . $campaignId;
?>
<div class="player-trigger-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Player Trigger', [$create], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'Player',
                'attribute' => 'playerId',
                'format' => 'text',
                'value' => function($model) {
                    $player = CampaignPlayer::findOne($model->playerId);
                    if (!empty($player->name)) {
                        return $player->name;
                    }
                    return $model->playerId;
                }
            ],
            'name',
            [
                'label' => 'Category',
                'attribute' => 'category',
                'format' => 'text',
                'value' => function($model) {
                    return $model->getCategoryName();
                }
            ],
            //'description:ntext',
            //'owner',
            //'creator',
            //'created',
            //'updated',
            'updated:datetime',
            [
                'label' => '',
                'attribute' => '',
                'format' => 'raw',
                'value' => function($model) {
                    $link = "/frontend/web/player-trigger/view";
                    $link .= "?campaignId=" . $_GET['campaignId'];
                    $link .= "&id=" . $model->id; 
                    $html = '<a class="btn btn-primary" href="'.$link.'">Manage</a>';
                    return $html;
                },
            ],
        ],
    ]); ?>


</div>
