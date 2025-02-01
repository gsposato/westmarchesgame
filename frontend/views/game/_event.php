<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\GamePoll;
use common\models\GamePollSlot;
use common\models\GameEvent;
use common\models\GamePlayer;
use common\models\CampaignPlayer;

$id = $_GET['campaignId'];
$owner = CampaignPlayer::find()->where(["userId" => $model->owner])->one();
$createGameEvent = '/frontend/web/game/event?campaignId=' . $id . '&id=' . $model->id;
$createGamePlayer = '/frontend/web/game/player?campaignId=' . $id . '&id=' . $model->id;
$changeGamePlayerStatus = '/frontend/web/game/playerstatus?campaignId=' . $id . '&id=' . $model->id;
$gamePoll = GamePoll::find()->where(["gameId" => $model->id])->one();
$gamePollSlots = array();
if (!empty($gamePoll->id)) {
    $gamePollSlots = GamePollSlot::find()->where(["gamePollId" => $gamePoll->id])->all();
}
$gameEvent = GameEvent::find()->where(["gameId" => $model->id])->one();
$gamePlayers = array();
if (!empty($gameEvent)) {
    $gamePlayers = GamePlayer::organize($model->id);
}
?>

    <?php if ($canModify): ?>
        <?php if (empty($gameEvent)): ?>
            <?php $gameEvent = new GameEvent(); ?>
            <div class="card">
                <div class="card-header">
                    <b>Game Event</b>
                </div>
                <div class="card-body">
                        <p>Choose one of the timestamps below to schedule your game:</p>
                        <?php foreach ($gamePollSlots as $gamePollSlot): ?>
                            <?php $url = $createGameEvent . "&slotId=" . $gamePollSlot->id; ?>
                            <a href="<?= $url; ?>" class="btn btn-secondary" style="margin:5px">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <?= date("m/d/Y h:i a", $gamePollSlot->unixtime); ?>
                            </a>
                        <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
                <div class="card">
                    <div class="card-header">
                        <b>Game Event</b>
                        <button id="gameevent-text-btn" onclick="copyText('gameevent-text')" class="btn btn-primary" style="float:right;">
                            <i class="fa fa-copy"></i>&nbsp;Copy
                        </button>
                    </div>
                    <div class="card-body" style="background-color:#333;color:#fff">
<?php $slot = GamePollSlot::findOne($gameEvent->gamePollSlotId); ?>
<?php $timestamp = $slot->unixtime; ?>
<pre id="gameevent-text" style="overflow-x:hidden;">
**<?= $model->name; ?>**
*Hosted by* @<?= $owner->name; ?> 
<?php if (!empty($gamePlayers)): ?>
<?php foreach ($gamePlayers as $gamePlayer): ?>
<?php if ($gamePlayer->status == GamePlayer::STATUS_COHOST): ?>
*Co-Host* @<?= $gamePlayer->name() . "\n"; ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

**Game Invite Link:** <?= $model->gameInviteLink; ?> 
**Venue:** <?= $model->voiceVenueLink; ?> 
**Levels:** <?= $model->levelRange; ?> 
**Duration:** <?= $model->timeDuration; ?> 
**Date/Time:** &lt;t:<?= $timestamp; ?>:F&gt;
<?php if (!empty($gamePlayers)): ?> 
**Players** 
<?php foreach ($gamePlayers as $gamePlayer): ?>
<?php if ($gamePlayer->status == GamePlayer::STATUS_SCHEDULED): ?>
<?php if ($gamePlayer->userId != $owner->id): ?>
@<?= $gamePlayer->name() . "\n"; ?>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($gamePlayers)): ?> 
**Reserve** 
<?php foreach ($gamePlayers as $gamePlayer): ?>
<?php if ($gamePlayer->status == GamePlayer::STATUS_RESERVED): ?>
@<?= $gamePlayer->name() . "\n"; ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
</pre>
                    </div>
                    <div class="card-footer">
                        <?php if (!empty($gamePlayers)): ?>
                            <small style="color:#aaa">
                                <i class="fa fa-check"></i>&nbsp;Scheduled&nbsp;&nbsp;&nbsp;&nbsp;
                                <i class="fa fa-clock"></i>&nbsp;Reserved&nbsp;&nbsp;&nbsp;&nbsp;
                                <i class="fa fa-minus"></i>&nbsp;Dropout&nbsp;&nbsp;&nbsp;&nbsp;
                                <i class="fa fa-chevron-up"></i>&nbsp;Activated&nbsp;&nbsp;&nbsp;&nbsp;
                                <i class="fa fa-fire-flame-curved"></i>&nbsp;Co-Host&nbsp;&nbsp;&nbsp;&nbsp;
                            </small>
                            <br />
                            <?php foreach ($gamePlayers as $gamePlayer): ?>
                                <?php if ($gamePlayer->userId == $owner->id): ?>
                                    <?php continue; ?>
                                <?php endif; ?>
                                <?php $url = $changeGamePlayerStatus; ?>
                                <?php $url .= "&gamePlayerId=" . $gamePlayer->id; ?>
                                <?php $color = $gamePlayer->statusColor(); ?>
                                <?php $icon = $gamePlayer->statusIcon(); ?>
                                <a href="<?= $url; ?>" class="btn <?= $color; ?>" style="margin:5px">
                                    <i class="fa <?= $icon; ?>"></i>&nbsp;<?= $gamePlayer->name(); ?>
                                    <?php $cp = CampaignPlayer::findOne($gamePlayer->userId); ?>
                                    <?= " • " . date("m/d/Y", $cp->gameEventTimestamp) . " • "; ?>
                                    <?= $cp->played(); ?>
                                </a>
                            <?php endforeach; ?>
                            <hr />
                        <?php endif; ?>
                        <?php $gamePlayer = new GamePlayer(); ?>
                        <?php $campaignPlayer = new CampaignPlayer(); ?>
                        <?php $select = $campaignPlayer->select(); ?>
                        <?php $options = ['prompt' => '']; ?>
                        <?php $form = ActiveForm::begin(["action" => $createGamePlayer]); ?>
                            <?= $form->field($gamePlayer, 'userId')->dropDownList($select, $options); ?>
                            <br />
                            <?= Html::submitButton('Add Player', ['class' => 'btn btn-success']) ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
        <?php endif; ?>
    <?php endif; ?>
