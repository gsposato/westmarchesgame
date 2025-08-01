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

    <p>Click on a character's name for more information about them.</p>

    <?php foreach ($alerts as $type => $message): ?>
        <div class="alert alert-<?= $type; ?>">
            <?= $message; ?>
        </div>
    <?php endforeach; ?>

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
            [
                'label' => 'Name',
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($model) {
                    $campaignId = $_GET['campaignId'];
                    $view = '/frontend/web/campaign-character/view?campaignId=' . $campaignId . '&id=';
                    return '<a href="' . $view . $model->id . '">' . $model->name . '</a>';
                }
            ],
            [
                'label' => 'Bastion Points',
                'attribute' => '',
                'format' => 'text',
                'value' => function($model) {
                    return $model->getRemainingBastionPoints();
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
                    return CampaignCharacter::advancement($campaignId, $gamesPlayed, $model);
                }
            ],
            [
                'label' => 'Game Credit',
                'attribute' => '',
                'format' => 'text',
                'value' => function($model) {
                    return $model->getTotalGameCredit();
                }
            ],
            [
                'label' => 'Last Game',
                'attribute' => '',
                'format' => 'text',
                'value' => function($model) {
                    return CampaignCharacter::previous($model->id);
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
                        $user = User::findOne($game->host());
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
