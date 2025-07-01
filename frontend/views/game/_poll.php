<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\GamePoll;
use common\models\GamePollSlot;
use common\models\CampaignPlayer;
use common\widgets\Alert;
use frontend\helpers\ControllerHelper;

$id = $_GET['campaignId'];
$owner = CampaignPlayer::find()->where(["userId" => $model->host()])->one();
$createGamePoll = '/frontend/web/game/poll?campaignId=' . $id . '&id=' . $model->id;
$createGamePollSlot = '#';
$deleteGamePollSlot = '#';
$gamePoll = GamePoll::find()->where(["gameId" => $model->id])->one();
$gamePollSlots = array();
if (!empty($gamePoll->id)) {
    $createGamePollSlot = '/frontend/web/game/pollslot?campaignId=' . $id . '&id=' . $model->id;
    $deleteGamePollSlot = '/frontend/web/game/pollslotdelete?campaignId=' . $id . '&id=' . $model->id;
    $gamePollSlots = GamePollSlot::find()->where(["gamePollId" => $gamePoll->id])->all();
}
?>

    <?php if ($canModify): ?>
        <?php if (empty($gamePoll)): ?>
            <?php $gamePoll = new GamePoll(); ?>
            <div class="card">
                <div class="card-header">
                    <b>Game Poll</b>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(["action" => $createGamePoll]); ?>
                        <?= $form->field($gamePoll, 'note')->textArea(["rows" => 6]) ?>
                        <p><small><em>Briefly describe your game.</em></small></p>
                        <br />
                        <?= Html::submitButton('Create Game Poll', ['class' => 'btn btn-success']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        <?php else: ?>
                <div class="card">
                    <div class="card-header">
                        <b>Game Poll</b>
                        <button id="gamepoll-text-btn" onclick="copyText('gamepoll-text')" class="btn btn-primary" style="float:right;">
                            <i class="fa fa-copy"></i>&nbsp;Copy
                        </button>
                    </div>
                    <div class="card-body" style="background-color:#333;color:#fff">
<pre id="gamepoll-text" style="overflow-x:hidden;">
**<?= $model->name; ?>**
DM @<?= $owner->name; ?> 
Game Invite Link: <?= $model->gameInviteLink; ?> 
Levels: <?= $model->levelRange; ?> 
Duration: <?= $model->timeDuration; ?> 

<?= $gamePoll->note; ?> 
 
<?php foreach ($gamePollSlots as $gamePollSlot): ?>
&lt;t:<?= $gamePollSlot->unixtime; ?>:F&gt;
<?php endforeach; ?>

---
[Check your character's level and bastion points](<?= ControllerHelper::url(); ?>/frontend/web/campaign-character/roundup?campaignId=<?= $id; ?>)
---
</pre>
                    </div>
                    <div class="card-footer">
                        <?php foreach ($gamePollSlots as $gamePollSlot): ?>
                            <?php $url = $deleteGamePollSlot . "&slotId=" . $gamePollSlot->id; ?>
                            <a href="<?= $url; ?>" class="btn btn-danger" style="margin:5px">
                                <i class="fa fa-times"></i>&nbsp;
                                <?= date("m/d/Y h:i a", $gamePollSlot->unixtime); ?>
                            </a>
                        <?php endforeach; ?>
                        <hr />
                        <?php $gamePollSlot = new GamePollSlot(); ?>
                        <?php $form = ActiveForm::begin(["action" => $createGamePollSlot]); ?>
                            <?= Alert::widget() ?>
                            <?php $textInput = ["type" => "datetime-local"]; ?>
                            <?= $form->field($gamePollSlot, 'humantime')->textInput($textInput) ?>
                            <div class="form-group" style="padding-top:15px;padding-bottom:15px;">
                                <?= Html::submitButton('Add Timestamp', ['class' => 'btn btn-success']) ?>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
        <?php endif; ?>
    <?php endif; ?>
