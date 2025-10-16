<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\GameEvent;
use common\models\GamePlayer;
use common\models\CampaignPlayer;
use common\models\CampaignCharacter;

$id = $_GET['campaignId'];
$gameEvent = GameEvent::find()->where(["gameId" => $model->id])->one();
$gameRoundupNote = '/frontend/web/game/roundupnote?campaignId=' . $id . '&id=' . $model->id;
$gamePlayers = array();
if (!empty($gameEvent)) {
    $gamePlayers = GamePlayer::organize($model->id);
}
?>

    <?php if ($canModify): ?>
        <?php if (!empty($gamePlayers)): ?>
            <div class="card">
                <div class="card-header">
                    <b>Game Roundup</b>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(["action" => $gameRoundupNote]); ?>
                        <?php $attributes = ['maxlength' => true, 'rows' => 3]; ?>
                        <?php if (empty($model->gameRoundupNote)): ?>
                            <?php $attributes['placeholder'] = 'In which...'; ?>
                        <?php endif; ?>
                        <?= $form->field($model, 'gameRoundupNote')->textArea($attributes) ?>
                        <?= Html::submitButton('Save Changes', ['class' => 'btn btn-success']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
    <?php endif; ?>
<?php endif; ?>
