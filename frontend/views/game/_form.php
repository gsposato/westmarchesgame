<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use common\models\Campaign;
use common\models\GamePoll;

/** @var yii\web\View $this */
/** @var common\models\Game $model */
/** @var yii\widgets\ActiveForm $form */
$campaignId = $_GET['campaignId'];
$campaign = Campaign::findOne($campaignId);
$rules = json_decode($campaign->rules);
$defaultTimeDuration = $rules->Game->defaultTimeDuration ?? "4 hours";
$defaultGoldPayoutPerPlayer = $rules->Game->defaultGoldPayoutPerPlayer ?? 400;
$defaultBaseBastionPointsPerPlayer = $rules->Game->defaultBaseBastionPointsPerPlayer ?? 20;
$defaultBonusBastionPointsPerPlayer = $rules->Game->defaultBonusBastionPointsPerPlayer ?? 5;
$defaultGameCreditPerPlayer = $rules->Game->defaultGameCreditPerPlayer ?? 1;
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

    <?php $options = [
            'maxlength' => true,
            'value' => $model->timeDuration ?? $defaultTimeDuration
        ];
    ?>
    <?= $form->field($model, 'timeDuration')->textInput($options) ?>
    <small>
        <p>
            <em>
                Typically, it's <?= $defaultTimeDuration; ?>
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

    <?php $options = [
            'type' => 'number',
            'value' => $model->goldPayoutPerPlayer ?? $defaultGoldPayoutPerPlayer
        ];
    ?>
    <?= $form->field($model, 'goldPayoutPerPlayer')->textInput($options) ?>
    <small>
        <p>
            <em>
                Typically, its <?= $defaultGoldPayoutPerPlayer; ?>
            </em>
        </p>
    </small>

    <?php $options = [
            'type' => 'number',
            'value' => $model->baseBastionPointsPerPlayer ?? $defaultBaseBastionPointsPerPlayer
        ];
    ?>
    <?= $form->field($model, 'baseBastionPointsPerPlayer')->textInput($options) ?>
    <small>
        <p>
            <em>
                Typically, its <?= $defaultBaseBastionPointsPerPlayer; ?>
            </em>
        </p>
    </small>

    <?php $options = [
            'type' => 'number',
            'value' => $model->bonusBastionPointsPerPlayer ?? $defaultBonusBastionPointsPerPlayer
        ];
    ?>
    <?= $form->field($model, 'bonusBastionPointsPerPlayer')->textInput($options) ?>
    <small>
        <p>
            <em>
                Typically, its <?= $defaultBonusBastionPointsPerPlayer; ?>
            </em>
        </p>
    </small>

    <?php $options = [
            'type' => 'number',
            'value' => $model->credit ?? $defaultGameCreditPerPlayer
        ];
    ?>
    <?= $form->field($model, 'credit')->textInput($options) ?>
    <small>
        <p>
            <em>
                Typically, one game is worth <?= $defaultGameCreditPerPlayer; ?> credit.
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

    <?php if ($model->canNotarize()): ?>
        <?php $userSelect = User::select(); ?>
        <?= $form->field($model, 'owner')->dropDownList($userSelect, ['prompt' => '']); ?>
        <input type="hidden" name="notarizeKey" value="<?= $model->getNotarizeKey(); ?>" />
        <br />
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
