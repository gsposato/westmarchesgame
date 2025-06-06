<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\GamePoll;
use common\models\GamePollSlot;
use common\models\GameEvent;
use common\models\GamePlayer;
use common\models\CampaignPlayer;
use common\models\PlayerTrigger;
use frontend\helpers\ControllerHelper;

$id = $_GET['campaignId'];
$owner = CampaignPlayer::find()->where(["userId" => $model->host()])->one();
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
$triggers = array();
if (!empty($gameEvent)) {
    $gamePlayers = GamePlayer::organize($model->id);
}
if (!empty($gamePlayers)) {
    foreach ($gamePlayers as $player) {
        if ($player->status == GamePlayer::STATUS_DROPOUT) {
            continue;
        }
        $myTriggers = PlayerTrigger::find()->where(["playerId" => $player->userId])->all();
        foreach ($myTriggers as $trigger) {
            array_push($triggers, $trigger);
        }
    }
}
?>
    <?php if ($canModify): ?>
        <?php if (!empty($gameEvent)): ?>
                <div class="card">
                    <div class="card-header">
                        <b>Game Triggers</b>
  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseGameTrigger" aria-expanded="false" aria-controls="collapseGameTrigger" style="float:right;">
Show/Hide
  </button>
                    </div>
                    <div class="collapse" id="collapseGameTrigger">
                    <div class="card-body">
                        <?php if (empty($gamePlayers)): ?>
                            No Players Added.  Add Players to see Triggers.
                        <?php elseif (empty($triggers)): ?>
                            No Triggers were Found.
                        <?php else: ?>
                            <?php foreach ($triggers as $trigger): ?>
                                <?php $myTrigger = $trigger; ?>
                                <div class="alert alert-<?= $trigger->getCategoryThing('alert'); ?>">
                                    <h6>
                                        <i class="fa <?= $trigger->getCategoryThing('icon'); ?>"></i>&nbsp;
                                        <?= $trigger->name; ?>
                                    </h6>
                                    <p>
                                        <?= $trigger->description; ?>
                                    </p>
                                    <?php foreach ($gamePlayers as $player): ?>
                                        <?php $cp = CampaignPlayer::findOne($player->userId); ?>
                                        <?php if ($player->userId == $trigger->playerId && !empty($cp)): ?>
                                            <i class="fa fa-user"></i>&nbsp;<?= $cp->name; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                            <?php if (!empty($myTrigger)): ?>
                            <small style="color:#aaa">
                                <?php foreach ($myTrigger->getCategoryThings() as $name => $thing): ?>
                                    <i class="fa <?= $thing['icon']; ?>"></i>&nbsp;<?= $name; ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php endforeach; ?>
                            </small>
                            &nbsp;
                            <?php endif; ?>
                    </div>
                    </div>
                </div>
        <?php endif; ?>
    <?php endif; ?>
