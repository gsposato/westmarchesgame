<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\GamePoll;

/** @var yii\web\View $this */
/** @var common\models\Game $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="game-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <small>
        <p>
            <em>
                Pick something fun that hints at what this game will be about
            </em>
        </p>
    </small>

    <?= $form->field($model, 'gameInviteLink')->textInput() ?>
    <small>
        <p>
            <em>
                You can provide the Roll20 invite link for your game, helpful for new players
            </em>
        </p>
    </small>

    <?= $form->field($model, 'voiceVenueLink')->textInput() ?>
    <small>
        <p>
            <em>
                You can provide a direct link to the discord voice channel, helpful for new players
            </em>
        </p>
    </small>
    
    <?= $form->field($model, 'timeDuration')->textInput(['maxlength' => true]) ?>
    <small>
        <p>
            <em>
                Typically, it's 4-5 hours
            </em>
        </p>
    </small>

    <?= $form->field($model, 'levelRange')->textInput(['maxlength' => true]) ?>
    <small>
        <p>
            <em>
                Example: 3, 4, 5
            </em>
        </p>
    </small>

    <?= $form->field($model, 'goldPayoutPerPlayer')->textInput(["type" => "number"]) ?>
    <small>
        <p>
            <em>
                Typically, its 400
            </em>
        </p>
    </small>

    <?= $form->field($model, 'credit')->textInput(["type"=>"number"]) ?>
    <small>
        <p>
            <em>
                Credit represents the amount of game credit earned.  Typically, one game is worth one credit.
            </em>
        </p>
    </small>

    <?php $gamePoll = GamePoll::find()->where(["gameId" => $model->id])->one(); ?>
    <?php if (!empty($gamePoll->note)): ?>
        <div class="form-group">
        <label for="gamepoll-note">Game Poll Note</label>
        <textarea id="gamepoll-note" name="GamePoll[note]" rows="6" class="form-control">
<?= $gamePoll->note; ?>
        </textarea>
            <em>
                The description that appears in your Game Poll text
            </em>
        </div>
    <?php endif; ?>

    <br />

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
