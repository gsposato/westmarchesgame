<?php

use common\models\Game;
use common\models\GameEvent;
use common\models\GamePollSlot;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Games';
$this->params['breadcrumbs'][] = $this->title;
$campaignId = $_GET['campaignId'];
$create = 'create?campaignId=' . $campaignId;
$roundup = 'roundup?campaignId=' . $campaignId;
?>
<div class="game-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Game', [$create], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Roundup', [$roundup], ['class' => 'btn btn-secondary']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'Session ID',
                'attribute' => 'id',
                'format' => 'raw',
                'value' => function($model) {
                    return Game::session($model->id);
                },
            ],
            'name',
            //'levelRange',
            //'owner',
            [
                'label' => 'Event Timestamp',
                'attribute' => '',
                'format' => 'raw',
                'value' => function($model) {
                    return Game::event($model->id);
                },
            ],
            'updated:datetime',
            [
                'label' => '',
                'attribute' => '',
                'format' => 'raw',
                'value' => function($model) {
                    $link = "/frontend/web/game/view";
                    $link .= "?campaignId=" . $_GET['campaignId'];
                    $link .= "&id=" . $model->id; 
                    $html = '<a class="btn btn-primary" href="'.$link.'">Manage</a>';
                    return $html;
                },
            ],
        ],
    ]); ?>


</div>
