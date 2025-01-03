<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use common\models\GamePoll;
use common\models\GamePollSlot;

/** @var yii\web\View $this */
/** @var common\models\Game $model */

$id = $_GET['campaignId'];
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Games', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$canModify = $model->canModify();
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
\yii\web\YiiAsset::register($this);
?>
<div class="game-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($canModify): ?>
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
            'campaignId',
            'name',
            'gameInviteLink:ntext',
            'voiceVenueLink:ntext',
            'timeDuration',
            'levelRange',
            'goldPayoutPerPlayer',
            'credit',
        ],
        $model->view()
        )
    ]) ?>
    <div id="gamepoll">
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
**Tavern Rescue: Melodious Muse**
DM @<?= $model->owner(); ?> 
Game Invite Link: <?= $model->gameInviteLink; ?> 
Gold Per Player: <?= $model->goldPayoutPerPlayer; ?> 
Levels: <?= $model->levelRange; ?> 
Duration: <?= $model->timeDuration; ?> 

<?= $gamePoll->note; ?> 
 
<?php foreach ($gamePollSlots as $gamePollSlot): ?>
&lt;t:<?= $gamePollSlot->unixtime; ?>:F&gt;
<?php endforeach; ?>
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
    </div>
</div>
<script type="text/javascript">
function copyText(id) {
  btn = "#"+id+"-btn";
  try {
      navigator.clipboard.writeText(document.getElementById(id).innerHtml);
      alert("Text Copied!");
      $(btn).removeClass("btn-primary");
      $(btn).addClass("btn-success");
      $(btn).html('<i class="fa fa-copy"></i>&nbsp;Copied!');
  } catch (err) {
      $(btn).removeClass("btn-primary");
      $(btn).addClass("btn-danger");
      $(btn).html('<i class="fa fa-copy"></i>&nbsp;Failed!');
      console.log(err);
  }
}
</script>
