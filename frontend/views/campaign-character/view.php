<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Purchase;
use common\models\Campaign;
use common\models\CampaignCharacter;
use common\models\CampaignPlayer;
use common\models\GamePlayer;
use common\models\Game;

/** @var yii\web\View $this */
/** @var common\models\CampaignCharacter $model */

$id = $_GET['campaignId'];
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Campaign Characters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$campaign = Campaign::findOne($id);
$campaignRules = json_decode($campaign->rules);
$purchases = Purchase::find()->where(["characterId" => $model->id])->all();
$gamesPlayed = GamePlayer::find()->where(["characterId" => $model->id])->all();
$characterAdvancement = CampaignCharacter::advancement($id, $gamesPlayed);
$totalGoldEarned = $campaignRules->CampaignCharacter->startingGold ?? 0;
$totalBastionPointsEarned = $campaignRules->CampaignCharacter->startingBastionPoints ?? 0;
$totalCreditsEarned = 0;
$totalGoldSpent = 0;
$totalBastionPointsSpent = 0;
\yii\web\YiiAsset::register($this);
?>
<div class="campaign-character-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($model->canModify()): ?>
        <?= Html::a('Update', ['update?campaignId='.$id.'&id='.$model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete?campaignId='.$id.'&id='.$model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge(
            [
            'id',
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
                'label' => 'Type',
                'attribute' => 'type',
                'format' => 'text',
                'value' => function($model) {
                    $type = CampaignCharacter::type();
                    if (!empty($type[$model->type])) {
                        return $type[$model->type];
                    }
                    return $model->type;
                }
            ],
            [
                'label' => 'Status',
                'attribute' => 'status',
                'format' => 'text',
                'value' => function($model) {
                    $type = CampaignCharacter::status();
                    if (!empty($type[$model->status])) {
                        return $type[$model->status];
                    }
                    return $model->status;
                }
            ],
            'description:ntext',
            'bastionName:ntext',
            'bastionType:ntext',
            ],
            $model->view()
        )
    ]) ?>

</div>

            <br />

            <div class="card">
                <div class="card-header">
                    <b>Game History</b>
                    <b style="float: right;">
                        <button class="btn btn-secondary">
                            Character Level: <?= $characterAdvancement; ?>
                        </button>
                    </b>
                </div>
                <div class="card-body">
                <ol>
                <?php foreach ($gamesPlayed as $gamePlayed): ?>
                    <?php $game = Game::findOne($gamePlayed->gameId); ?>
                    <?php if (!$game->isEnded()): ?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <li>
                        Session #<?= $game->id ?> - <?= $game->name; ?> /
                        <small style="font-weight:bold;">
                            <?= $game->credit; ?> credit<?= $game->credit == 1 ? "" : "s"; ?>
                            <?php $totalCreditsEarned += $game->credit; ?>
                        </small> /
                        <small style="font-weight:bold;color:#df8607;">
                            <?= $game->goldPayoutPerPlayer; ?> gold
                            <?php $totalGoldEarned += $game->goldPayoutPerPlayer; ?>
                        </small> /
                        <small style="font-weight:bold;">
                            <?php $bastionPoints = 0; ?>
                            <?php $bastionPoints += $game->baseBastionPointsPerPlayer; ?>
                            <?php if (!empty($gamePlayed->hasBonusPoints)): ?>
                                <?php $bastionPoints += $game->bonusBastionPointsPerPlayer; ?>
                            <?php endif; ?>
                            <?= $bastionPoints; ?> bastion points
                            <?php $totalBastionPointsEarned += $bastionPoints; ?>
                        </small>
                    </li>
                <?php endforeach; ?>
                </ol>
                </div>
                <div class="card-footer">
                    Total Credits Earned: <b><?= $totalCreditsEarned; ?></b> /
                    Total Gold Earned: <b style="color:#df8607"><?= $totalGoldEarned; ?></b> /
                    Total Bastion Points Earned: <b><?= $totalBastionPointsEarned; ?></b>
                </div>
            </div>

            <br />

            <br />

            <div class="card">
                <div class="card-header">
                    <b>Purchase History</b>
                    <b style="float: right;">
                        <button class="btn btn-secondary">
                            Character Level: <?= $characterAdvancement; ?>
                        </button>
                    </b>
                </div>
                <div class="card-body">
                <ul>
                    <?php foreach ($purchases as $purchase): ?>
                        <?php if ($purchase->currency == 1): ?>
                            <?php $color = "#df8607"; ?>
                            <?php $currency = "gold"; ?>
                            <?php $totalGoldSpent += $purchase->price; ?>
                        <?php endif; ?>
                        <?php if ($purchase->currency == 2): ?>
                            <?php $color = "#000"; ?>
                            <?php $currency = "bastion points"; ?>
                            <?php $totalBastionPointsSpent += $purchase->price; ?>
                        <?php endif; ?>
                        <li>
                            <?= $purchase->name; ?> /
                            <small style="font-weight:bold;color:<?= $color; ?>;">
                                <?= $purchase->price; ?> <?= $currency; ?>
                            </small>
                        </li>
                    <?php endforeach; ?>
                </ul>
                </div>
                <div class="card-footer">
                    Total Gold Spent: <b style="color:#df8607"><?= $totalGoldSpent; ?></b> /
                    Total Bastion Points Spent: <b><?= $totalBastionPointsSpent; ?></b>
                </div>
            </div>
