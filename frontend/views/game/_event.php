<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\GamePoll;
use common\models\GamePollSlot;
use common\models\GameEvent;
use common\models\GamePlayer;
use common\models\CampaignPlayer;

$id = $_GET['campaignId'];
$createGameEvent = '/frontend/web/game/event?campaignId=' . $id . '&id=' . $model->id;
$createGamePlayer = '/frontend/web/game/player?campaignId=' . $id . '&id=' . $model->id;
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
<pre id="gamepoll-text" style="overflow-x:hidden;">
**<?= $model->name; ?>**
*Hosted by* @<?= $model->owner(); ?> 

**Game Invite Link:** <?= $model->gameInviteLink; ?> 
**Venue:** <?= $model->voiceVenueLink; ?> 
**Levels:** <?= $model->levelRange; ?> 
**Duration:** <?= $model->timeDuration; ?> 
**Date/Time:** &lt;t:<?= $timestamp; ?>:F&gt;
<?php if (!empty($gamePlayers)): ?> 
**Players** 
<?php foreach ($gamePlayers as $gamePlayer): ?>
@<?= $gamePlayer->name() . "\n"; ?>
<?php endforeach; ?>
<?php endif; ?>
</pre>
                    </div>
                    <div class="card-footer">
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
