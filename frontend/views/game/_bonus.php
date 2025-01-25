<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\GameEvent;
use common\models\GamePlayer;
use common\models\CampaignPlayer;
use common\models\CampaignCharacter;

$id = $_GET['campaignId'];
$addRemoveBonus = '/frontend/web/game/bonus?campaignId=' . $id . '&id=' . $model->id;
$gameEvent = GameEvent::find()->where(["gameId" => $model->id])->one();
$gamePlayers = array();
if (!empty($gameEvent)) {
    $gamePlayers = GamePlayer::organize($model->id);
}
?>

    <?php if ($canModify): ?>
        <?php if (!empty($gamePlayers)): ?>
            <div class="card">
                <div class="card-header">
                    <b>Game Bonus</b>
                </div>
                <div class="card-body">
                        <p>Choose the type of bonus characters receive for this game:</p>
                        <?php foreach ($gamePlayers as $gamePlayer): ?>
                            <?php $character = CampaignCharacter::findOne($gamePlayer->characterId); ?>
                            <?php if (empty($character)): ?>
                                <?php continue; ?>
                            <?php endif; ?>
                            <?php $url = $addRemoveBonus . "&characterId=" . $character->id; ?>
                            <?php $bonus = $gamePlayer->hasBonusPoints; ?>
                            <?php if ($bonus == GamePlayer::BONUS_NORMAL): ?>
                                <a href="<?= $url; ?>" class="btn btn-success" style="margin:5px">
                                    <i class="fa fa-check"></i>&nbsp;<?= $character->name; ?>
                                </a>
                            <?php elseif($bonus == GamePlayer::BONUS_BASTION): ?>
                                <a href="<?= $url; ?>" class="btn btn-primary" style="margin:5px">
                                    <i class="fa fa-house"></i>&nbsp;<?= $character->name; ?>
                                </a>
                            <?php elseif($bonus == GamePlayer::BONUS_DOUBLE_GOLD): ?>
                                <a href="<?= $url; ?>" class="btn btn-warning" style="margin:5px">
                                    <i class="fa fa-coins"></i>&nbsp;<?= $character->name; ?>
                                </a>
                            <?php elseif($bonus == GamePlayer::BONUS_DOUBLE_GOLD_BASTION): ?>
                                <a href="<?= $url; ?>" class="btn btn-danger" style="margin:5px">
                                    <i class="fa fa-flag"></i>&nbsp;<?= $character->name; ?>
                                </a>
                            <?php else: ?>
                                <a href="<?= $url; ?>" class="btn btn-secondary" style="margin:5px">
                                    <i class="fa fa-minus"></i>&nbsp;<?= $character->name; ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                </div>
                <div class="card-footer">

                            <small style="color:#888">
                                <i class="fa fa-check"></i>&nbsp;Normal Bonus&nbsp;&nbsp;&nbsp;&nbsp;
                                <i class="fa fa-house"></i>&nbsp;Bastion Bonus&nbsp;&nbsp;&nbsp;&nbsp;
                                <i class="fa fa-coins"></i>&nbsp;Double Gold Bonus&nbsp;&nbsp;&nbsp;&nbsp;
                                <i class="fa fa-flag"></i>&nbsp;Double Gold Bastion Bonus&nbsp;&nbsp;&nbsp;&nbsp;
                                <i class="fa fa-minus"></i>&nbsp;Nothing&nbsp;&nbsp;&nbsp;&nbsp;
                            </small>
                            <br />
                </div>
            </div>

            <br />

            <div class="card">
                <div class="card-body">
                    <ul>
                        <li>
                            <b class="text-success">Normal Bonus</b>
                            <br />
                            The <i>Normal Bonus</i> will be this game's <b>Gold Payout Per Player</b>, this game's <b>Base Bastion Points Per Player</b>, and this game's <b>Credit</b>.
                        </li>
                        <br />
                        <li>
                            <b class="text-primary">Bastion Bonus</b>
                            <br />
                            The <i>Bastion Bonus</i> will be this game's <b>Gold Payout Per Player</b>, this game's <b>Base Bastion Points Per Player</b>, this game's <b>Bonus Bastion Points Per Player</b>, and this game's <b>Credit</b>.
                        </li>
                        <br />
                        <li>
                            <b style="color: #df8607;">Double Gold Bonus</b>
                            <br />
                            If the chosen character is a Host Character (HC), the <i>Double Gold Bonus</i> will be this game's <b>Gold Payout Per Player</b>, <u>doubled</u>.  Plus this game's <b>Base Bastion Points Per Player</b>.  The <i>Double Gold Bonus</i> is not available for a Player Character (PC).
                        </li>
                        <br />
                        <li>
                            <b class="text-danger">Double Gold Bastion Bonus</b>
                            <br />
                            If the chosen character is a Host Character (HC), the <i>Double Gold Bastion Bonus</i> will be this game's <b>Gold Payout Per Player</b>, <u>doubled</u>.  Plus this game's <b>Base Bastion Points Per Player</b> and this game's <b>Bonus Bastion Points Per Player</b>.  The <i>Double Gold Bastion Bonus</i> is not available for a Player Character (PC).
                        </li>
                        <br />
                        <li>
                            <b style="color:#666;">Nothing</b>
                            <br />
                            No bonuses are awarded to the chosen character.
                        </li>
                    </ul>
                </div>
            </div>
    <?php endif; ?>
<?php endif; ?>
