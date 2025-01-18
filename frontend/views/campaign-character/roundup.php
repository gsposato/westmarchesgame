<?php

use common\models\CampaignCharacter;
use common\models\CampaignPlayer;
use common\models\GamePollSlot;
use common\models\GamePlayer;
use common\models\GameEvent;
use common\models\Game;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Campaign Characters Roundup';
$this->params['breadcrumbs'][] = $this->title;
$campaignId = $_GET['campaignId'];
$create = 'create?campaignId=' . $campaignId;
$roundup = 'roundup?campaignId=' . $campaignId;
?>
<div class="campaign-character-index">

    <h1><?= Html::encode($this->title) ?></h1>

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
                'label' => 'Games Played',
                'attribute' => '',
                'format' => 'text',
                'value' => function($model) {
                    $gamesPlayed = GamePlayer::find()
                        ->where(["characterId" => $model->id])
                        ->all();
                    return count($gamesPlayed);
                }
            ],
            [
                'label' => 'Level',
                'attribute' => '',
                'format' => 'text',
                'value' => function($model) {
                    $campaignId = $_GET['campaignId'];
                    $gamesPlayed = GamePlayer::find()
                        ->where(["characterId" => $model->id])
                        ->all();
                    return CampaignCharacter::advancement($campaignId, $gamesPlayed);
                }
            ],
            [
                'label' => 'Last Game',
                'attribute' => '',
                'format' => 'text',
                'value' => function($model) {
                    $lastGamePlayed = GamePlayer::find()
                        ->where(["characterId" => $model->id])
                        ->orderBy(["id" => SORT_DESC])
                        ->one();
                    if (empty($lastGamePlayed)) {
                        return;
                    }
                    $gameEvent = GameEvent::find()
                        ->where(["gameId" => $lastGamePlayed->gameId])
                        ->one();
                    if (empty($gameEvent)) {
                        return;
                    }
                    $gamePollSlot = GamePollSlot::findOne($gameEvent->gamePollSlotId);
                    return date("m/d/Y", $gamePollSlot->unixtime);
                }
            ],
            [
                'label' => 'Hosts',
                'attribute' => '',
                'format' => 'raw',
                'value' => function($model) {
                    $gamesPlayed = GamePlayer::find()
                        ->where(["characterId" => $model->id])
                        ->all();
                    $hosts = array();
                    foreach ($gamesPlayed as $gamePlayed) {
                        $game = Game::findOne($gamePlayed->gameId);
                        if (empty($game)) {
                            continue;
                        }
                        $user = User::findOne($game->owner);
                        if (empty($user)) {
                            continue;
                        }
                        $player = CampaignPlayer::find()
                            ->where(["userId" => $user->id])
                            ->one();
                        if (empty($player)) {
                            continue;
                        }
                        if (empty($hosts[$player->name])) {
                            $hosts[$player->name] = 0;
                        }
                        $hosts[$player->name]++;
                    }
                    $string = "";
                    foreach ($hosts as $key => $value) {
                        $string .= $key . ": <b>" . $value . "</b>, ";
                    }
                    //return print_r($hosts, true);
                    return trim($string, ", ");
                }
            ],
            'updated:datetime',
        ],
    ]); ?>


</div>
