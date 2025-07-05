<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Purchase;
use common\models\Currency;
use common\models\Campaign;
use common\models\CampaignCharacter;
use common\models\CampaignPlayer;
use common\models\GamePlayer;
use common\models\Game;
use common\models\Equipment;
use common\models\EquipmentGoal;
use common\models\EquipmentGoalRequirement as Egr;

/** @var yii\web\View $this */
/** @var common\models\CampaignCharacter $model */

$id = $_GET['campaignId'];
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Campaign Characters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$campaign = Campaign::findOne($id);
$campaignRules = json_decode($campaign->rules);
$purchases = Purchase::find()->where(["characterId" => $model->id])->andWhere(["deleted" => 0])->all();
$currencies = Currency::find()->where(["campaignId" => $id])->all();
$gamesPlayed = GamePlayer::find()->where(["characterId" => $model->id])->all();
$characterAdvancement = CampaignCharacter::advancement($id, $gamesPlayed, $model);
$defaultStartingGold = $campaignRules->CampaignCharacter->startingGold ?? 100;
$totalGoldEarned = $model->startingGold ?? $defaultStartingGold;
$defaultStartingBastionPoints = $campaignRules->CampaignCharacter->startingBastionPoints ?? 25;
$totalBastionPointsEarned = $model->startingBastionPoints ?? $defaultStartingBastionPoints;
$totalCreditsEarned = $model->startingCredit ?? 0;
$totalGoldSpent = 0;
$totalBastionPointsSpent = 0;
$isCreditWorthy = [
    GamePlayer::BONUS_NORMAL,
    GamePlayer::BONUS_BASTION
];
$isBastionWorthy = [
    GamePlayer::BONUS_NORMAL,
    GamePlayer::BONUS_BASTION,
    GamePlayer::BONUS_DOUBLE_GOLD,
    GamePlayer::BONUS_DOUBLE_GOLD_BASTION
];
$isBonusBastionWorthy = [
    GamePlayer::BONUS_BASTION,
    GamePlayer::BONUS_DOUBLE_GOLD_BASTION
];
$isGoldWorthy = [
    GamePlayer::BONUS_NORMAL,
    GamePlayer::BONUS_BASTION,
    GamePlayer::BONUS_DOUBLE_GOLD,
    GamePlayer::BONUS_DOUBLE_GOLD_BASTION
];
$isDoubleGoldWorthy = [
    GamePlayer::BONUS_DOUBLE_GOLD,
    GamePlayer::BONUS_DOUBLE_GOLD_BASTION
];
$character = $model;
$state = Equipment::stateSelect();
$equips = Equipment::find()->where(["characterId" => $character->id])->all();
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
            'startingGold',
            'startingBastionPoints',
            'startingCredit',
            'firstGamePlayed:datetime',
            ],
            $model->view()
        )
    ]) ?>

</div>
            <?php if (!empty($equips)): ?>
            <br />
            <div class="card">
                <div class="card-header">
                    <b>Equipment</b>
                </div>
                <div class="card-body">
                    <?php foreach($equips as $equip): ?>
                        <div class="alert alert-warning">
                            <h6><i class="fa fa-shield"></i>&nbsp;<?= $equip->name ?? ""; ?></h6>
                            <p><?= $equip->description; ?></p>
                            <p><b>Goal:</b> <?= $state[$equip->state+1] ?? "None"; ?></p>
                            <?php $where = []; ?>
                            <?php $where["equipmentId"] = $equip->id; ?>
                            <?php if (!empty($state[$equip->state+1])): ?>
                                <?php $where["name"] = $state[$equip->state+1]; ?>
                            <?php else: ?>
                                <?php $where["name"] = $state[$equip->state]; ?>
                            <?php endif; ?>
                            <?php $goals = EquipmentGoal::find()->where($where)->all(); ?>
                            <?php foreach ($goals as $goal): ?>
                                <p><?= $goal->description; ?></p>
                                <?php $wh = []; ?>
                                <?php $wh["equipmentGoalId"] = $goal->id; ?> 
                                <?php $reqs = Egr::find()->where($wh)->all(); ?>
                                <?php foreach ($reqs as $req): ?>
                                    <p>
                                        <i class="fa fa-flag"></i>&nbsp;<?= $req->name; ?> / 
                                        <?= $req->progress; ?>%
                                    </p>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?> 
                </div>
                <div class="card-footer">
                    <small style="color:#888">
                        <i class="fa fa-shield"></i>&nbsp;Equipment Name&nbsp;&nbsp;&nbsp;&nbsp;
                        <i class="fa fa-flag"></i>&nbsp;Goal Requirement&nbsp;&nbsp;&nbsp;&nbsp;
                    </small>
                    <br />
                </div>
            </div>
            <br />
            <?php endif; ?>

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
                <?php $totals = array(); ?>
                <?php foreach ($gamesPlayed as $gamePlayed): ?>
                    <?php $game = Game::findOne($gamePlayed->gameId); ?>
                    <?php if (empty($game)): ?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <?php if (!$game->isEnded()): ?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <li>
                        <?php $sessionId = Game::session($game->id); ?>
                        Session #<?= $sessionId ?> - <?= $game->name; ?> /
                        <small style="font-weight:bold;">
                            <?php if (in_array($gamePlayed->hasBonusPoints, $isCreditWorthy)): ?>
                                <?= $game->credit; ?> credit<?= $game->credit == 1 ? "" : "s"; ?>
                                <?php $totalCreditsEarned += $game->credit; ?>
                            <?php else: ?>
                                0 credits
                            <?php endif; ?>
                        </small> /
                        <small style="font-weight:bold;color:#df8607;">
                            <?php $gold = 0; ?>
                            <?php if (in_array($gamePlayed->hasBonusPoints, $isGoldWorthy)): ?>
                                <?php $gold += $game->goldPayoutPerPlayer; ?>
                                <?php if (in_array($gamePlayed->hasBonusPoints, $isDoubleGoldWorthy)): ?>
                                    <?php $gold += $game->goldPayoutPerPlayer; ?>
                                <?php endif; ?>
                                <?= $gold; ?> gold
                                <?php $totalGoldEarned += $game->goldPayoutPerPlayer; ?>
                            <?php else: ?>
                                0 gold
                            <?php endif; ?>
                        </small> /
                        <small style="font-weight:bold;">
                            <?php $bastionPoints = 0; ?>
                            <?php if (in_array($gamePlayed->hasBonusPoints, $isBastionWorthy)): ?>
                                <?php $bastionPoints += $game->baseBastionPointsPerPlayer; ?>
                            <?php endif; ?>
                            <?php if (in_array($gamePlayed->hasBonusPoints, $isBonusBastionWorthy)): ?>
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
                    <?php foreach ($purchases as $purchase): ?>
                        <?php if (empty($totals[$purchase->currency])): ?>
                            <?php $totals[$purchase->currency] = 0; ?>
                        <?php endif; ?>
                        <?php if (!empty($purchase->gameId)): ?>
                            <?php $totals[$purchase->currency] += $purchase->price; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php foreach ($currencies as $currency): ?>
                         / Total <?= ucwords($currency->name); ?> Earned:
                        <b style="color:<?= $currency->color; ?>">
                            <?= $totals[$currency->id] ?? 0; ?>
                        </b>
                    <?php endforeach; ?>
                </div>
            </div>

            <br />

            <br />

            <div class="card">
                <div class="card-header">
                    <b>Transaction History</b>
                    <b style="float: right;">
                        <button class="btn btn-secondary">
                            Character Level: <?= $characterAdvancement; ?>
                        </button>
                    </b>
                </div>
                <div class="card-body">
                <ul>
                    <?php $totals = array(); ?>
                    <?php foreach ($purchases as $purchase): ?>
                        <?php $currencyColor = "#000"; ?>
                        <?php $currencyName = "unknown currency"; ?>
                        <?php foreach ($currencies as $currency): ?>
                            <?php if ($purchase->currency == 1): ?>
                                <?php $currencyColor = "#df8607"; ?>
                                <?php $currencyName = "gold"; ?>
                                <?php if (empty($totals["gold"])): ?>
                                    <?php $totals["gold"] = 0; ?>
                                <?php endif; ?>
                                <?php break; ?>
                            <?php endif; ?>
                            <?php if ($purchase->currency == 2): ?>
                                <?php $currencyColor = "#000"; ?>
                                <?php $currencyName = "bastion points"; ?>
                                <?php if (empty($totals["bastion points"])): ?>
                                    <?php $totals["bastion points"] = 0; ?>
                                <?php endif; ?>
                                <?php break; ?>
                            <?php endif; ?>
                            <?php if ($purchase->currency == 3): ?>
                                <?php $currencyColor = "#000"; ?>
                                <?php $currencyName = "credits"; ?>
                                <?php if (empty($totals["credits"])): ?>
                                    <?php $totals["credits"] = 0; ?>
                                <?php endif; ?>
                                <?php break; ?>
                            <?php endif; ?>
                            <?php if ($purchase->currency != $currency->id): ?>
                                <?php continue; ?>
                            <?php endif; ?>
                            <?php $currencyColor = $currency->color; ?>
                            <?php $currencyName = $currency->name; ?>
                            <?php if (empty($totals[$currency->name])): ?>
                                <?php $totals[$currency->name] = 0; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <li>
                            <?= $purchase->name; ?> /
                            <small style="font-weight:bold;color:<?= $currencyColor; ?>;">
                                <?= $purchase->price; ?> <?= $currencyName; ?>
                            </small>
                            <?php if (!empty($purchase->gameId)): ?>
                                <?php $sessionId = Game::session($purchase->gameId); ?>
                                / <small>Session #<?= $sessionId; ?></small>
                            <?php endif; ?>
                        </li>
                    <?php if (empty($purchase->gameId)): ?>
                        <?php $totals[$currencyName] += $purchase->price; ?>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                </div>
                <div class="card-footer">
                    Total Gold Spent: <b style="color:#df8607"><?= $totals["gold"] ?? 0; ?></b> /
                    Total Bastion Points Spent: <b><?= $totals["bastion points"] ?? 0; ?></b>
                    <?php foreach ($currencies as $currency): ?>
                         / Total <?= ucwords($currency->name); ?> Spent:
                        <b style="color:<?= $currency->color; ?>">
                            <?= $totals[$currency->name] ?? "0"; ?>
                        </b>
                    <?php endforeach; ?>
                </div>
            </div>
