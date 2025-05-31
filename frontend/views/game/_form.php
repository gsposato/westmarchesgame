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
$defaultTimeDurationTooltip = $rules->Game->defaultTimeDurationTooltip ?? "";
$defaultGoldPayoutPerPlayerTooltip = $rules->Game->defaultGoldPayoutPerPlayerTooltip ?? "";
$defaultBaseBastionPointsPerPlayerTooltip = $rules->Game->defaultBaseBastionPointsPerPlayerTooltip ?? "";
$defaultBonusBastionPointsPerPlayerTooltip = $rules->Game->defaultBonusBastionPointsPerPlayerTooltip ?? "";
$defaultGameCreditPerPlayerTooltip = $rules->Game->defaultGameCreditPerPlayerTooltip ?? "";
$userSelect = User::select();
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
                <?php if (!empty($defaultTimeDurationTooltip)): ?>
                    <?= $defaultTimeDurationTooltip; ?>
                <?php else: ?>
                    Typically, it's <?= $defaultTimeDuration; ?>
                <?php endif; ?>
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
                <?php if (!empty($defaultGoldPayoutPerPlayerTooltip)): ?>
                    <?= $defaultGoldPayoutPerPlayerTooltip; ?>
                <?php else: ?>
                    Typically, its <?= $defaultGoldPayoutPerPlayer; ?>
                <?php endif; ?>
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
                <?php if (!empty($defaultBaseBastionPointsPerPlayerTooltip)): ?>
                    <?= $defaultBaseBastionPointsPerPlayerTooltip; ?>
                <?php else: ?>
                    Typically, its <?= $defaultBaseBastionPointsPerPlayer; ?>
                <?php endif; ?>
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
                <?php if (!empty($defaultBonusBastionPointsPerPlayerTooltip)): ?>
                    <?= $defaultBonusBastionPointsPerPlayerTooltip; ?>
                <?php else: ?>
                    Typically, its <?= $defaultBonusBastionPointsPerPlayer; ?>
                <?php endif; ?>
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
                <?php if (!empty($defaultGameCreditPerPlayerTooltip)): ?>
                    <?= $defaultGameCreditPerPlayerTooltip; ?>
                <?php else: ?>
                    Typically, one game is worth <?= $defaultGameCreditPerPlayer; ?> credit.
                <?php endif; ?>
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

    <?= $form->field($model, 'host')->dropDownList($userSelect, ['prompt' => '']); ?>
    <em>Typically, this is you.  Sometimes, it's not.</em>

    <br />
    <br />

    <?php if ($model->canNotarize()): ?>
        <?= $form->field($model, 'owner')->dropDownList($userSelect, ['prompt' => '']); ?>
        <input type="hidden" name="notarizeKey" value="<?= $model->getNotarizeKey(); ?>" />
        <br />
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <br />
    <hr />
    <p>Save your changes above before using the buttons below.</p>
    <hr />
    <br />


    <div class="card text-white bg-dark">
        <div class="card-header">
            <b><i class="fa fa-cog"></i>&nbsp;Advanced Game Properties</b>
        </div>
        <div class="card-body">
            <b>Remove Game Event</b>
            <p>Removing the Game Event allows you to reschedule your game.</p>
            <?php $url = "/frontend/web/game"; ?>
            <?php $url .= "/removegameevent?campaignId=".$campaignId; ?>
            <?php $url .= "&id=".$model->id; ?>
            <a href="<?= $url; ?>" class="btn btn-danger">Remove Game Event</a>
        </div>
        <div class="card-footer">
        </div>
    </div>

</div>
