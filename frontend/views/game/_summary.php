<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Game;
use common\models\GameNote;
use common\models\GamePoll;
use common\models\GamePollSlot;
use common\models\GameEvent;
use common\models\GamePlayer;
use common\models\CampaignPlayer;
use common\models\CampaignCharacter;

$id = $_GET['campaignId'];
$owner = CampaignPlayer::find()->where(["userId" => $model->host()])->one();
$delete = '/frontend/web/game/deletenotes?campaignId=' . $id . '&id=' . $model->id;
$createGameNote = '/frontend/web/game/note?campaignId=' . $id . '&id=' . $model->id;
$gamePoll = GamePoll::find()->where(["gameId" => $model->id])->one();
$gamePollSlots = array();
if (!empty($gamePoll->id)) {
    $gamePollSlots = GamePollSlot::find()->where(["gamePollId" => $gamePoll->id])->all();
}
$gameEvent = GameEvent::find()->where(["gameId" => $model->id])->one();
$gamePlayers = array();
$gameNote = array();
if (!empty($gameEvent)) {
    $gamePlayers = GamePlayer::organize($model->id);
    $gameNote = GameNote::find()->where(["gameId" => $model->id])->all();
}
?>

    <?php if ($canModify): ?>
        <?php if (!empty($gameEvent)): ?>
                <div class="card">
                    <div class="card-header">
                        <b>Game Summary</b>
                        <button id="gamesummary-text-btn" onclick="copyText('gamesummary-text')" class="btn btn-primary" style="float:right;">
                            <i class="fa fa-copy"></i>&nbsp;Copy
                        </button>
                    </div>
                    <div class="card-body" style="background-color:#333;color:#fff!important;">
<?php $slot = GamePollSlot::findOne($gameEvent->gamePollSlotId); ?>
<?php $timestamp = $slot->unixtime; ?>
<?php $gamePlayer = GamePlayer::find()->where(["gameId" => $model->id, "userId" => $owner->id])->one(); ?>
<?php if (!empty($gamePlayer->characterId)): ?>
    <?php $character = CampaignCharacter::findOne($gamePlayer->characterId); ?>
<?php endif; ?>
<?php $characterName = $character->name ?? ""; ?>
<pre id="gamesummary-text" style="overflow-x:hidden;color#fff !important;">
**Session <?= Game::session($model->id); ?> - <?= $model->name; ?>**
**Date:** <?= date("m/d/Y", $timestamp); ?> 
**DM** <?= ucfirst($owner->name); ?> (<?= $characterName; ?>) 
<?php if (!empty($gamePlayers)): ?>
<?php foreach ($gamePlayers as $gamePlayer): ?>
<?php if ($gamePlayer->status == GamePlayer::STATUS_COHOST): ?>
<?php if (!empty($gamePlayer->characterId)): ?>
<?php $character = CampaignCharacter::findOne($gamePlayer->characterId); ?>
<?php endif; ?>
<?php $characterName = $character->name ?? ""; ?>
*CoDM* <?= ucfirst($gamePlayer->name()); ?> (<?= $characterName; ?>)
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($gamePlayers)): ?> 
**PCs** 
<?php foreach ($gamePlayers as $gamePlayer): ?>
<?php if ($gamePlayer->status == GamePlayer::STATUS_SCHEDULED): ?>
<?php if ($gamePlayer->userId != $owner->id): ?>
<?php $character = CampaignCharacter::findOne($gamePlayer->characterId); ?>
<?php $characterName = $character->name ?? ""; ?>
- <?= ucfirst($gamePlayer->name()) . ": " . $characterName . "\n"; ?>
<?php endif; ?>
<?php endif; ?>
<?php if ($gamePlayer->status == GamePlayer::STATUS_ACTIVATED): ?>
<?php $character = CampaignCharacter::findOne($gamePlayer->characterId); ?>
<?php $characterName = $character->name ?? ""; ?>
- <?= ucfirst($gamePlayer->name()) . ": " . $characterName . "\n"; ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

**Coin**
<?= $model->goldPayoutPerPlayer; ?> GP Per Player 

**Loot**
<?php foreach ($gameNote as $loot): ?>
<?php if ($loot->inGameSummary == 1): ?>
<?= $loot->note; ?> 
<?php endif; ?>
<?php endforeach; ?> 

**Highlights**
<?php foreach ($gameNote as $highlight): ?>
<?php if ($highlight->inGameSummary == 2): ?>
<?= $highlight->note; ?> 
<?php endif; ?>
<?php endforeach; ?> 
</pre>
                    </div>
                    <div class="card-footer">
                        <?php $gameNote = new GameNote(); ?>
                        <?php $form = ActiveForm::begin(["action" => $createGameNote]); ?>
                            <?= $form->field($gameNote, 'note')->textInput(); ?>
                            <?php $options = ['label' => 'as Loot', 'value' => '1', 'uncheck' => null]; ?>
                            <?= $form->field($gameNote, 'inGameSummary')->radio($options) ?> 
                            <?php $options = ['label' => 'as Highlight', 'value' => '2', 'uncheck' => null]; ?>
                            <?= $form->field($gameNote, 'inGameSummary')->radio($options) ?> 
                            <br />
                            <?= Html::submitButton('Add Note', ['class' => 'btn btn-success']) ?>
                            <a href="<?= $delete; ?>" class="btn btn-danger" style="float:right;">
                                Delete All Notes
                            </a>
                        <?php ActiveForm::end(); ?>
                    </div> 
                </div>
        <?php endif; ?>
    <?php endif; ?>
