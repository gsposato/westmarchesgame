<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\User;
use common\models\CampaignCharacter;

/** @var yii\web\View $this */
/** @var common\models\CampaignPlayer $model */
/** @var yii\widgets\ActiveForm $form */
$campaignId = $_GET['campaignId'];
$userSelect = User::select($all = true);
?>

<div class="campaign-player-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'userId')->dropDownList($userSelect, ['prompt' => '']); ?>

    <br />

    <div class="card">
        <div class="card-header">
        Is Player
        </div>
        <div class="card-body">
            <?= $form->field($model, 'isPlayer')->radio(['label' => 'True', 'value' => 1, 'uncheck' => null]) ?> 
            <?= $form->field($model, 'isPlayer')->radio(['label' => 'False', 'value' => 0, 'uncheck' => null]) ?> 
        </div>
    </div>

    <br />

    <div class="card">
        <div class="card-header">
        Is Host
        </div>
        <div class="card-body">
            <?= $form->field($model, 'isHost')->radio(['label' => 'True', 'value' => 1, 'uncheck' => null]) ?> 
            <?= $form->field($model, 'isHost')->radio(['label' => 'False', 'value' => 0, 'uncheck' => null]) ?> 
        </div>
    </div>

    <br />

    <div class="card">
        <div class="card-header">
        Is Support
        </div>
        <div class="card-body">
            <?= $form->field($model, 'isSupport')->radio(['label' => 'True', 'value' => 1, 'uncheck' => null]) ?> 
            <?= $form->field($model, 'isSupport')->radio(['label' => 'False', 'value' => 0, 'uncheck' => null]) ?> 
        </div>
    </div>

    <br />

    <div class="card">
        <div class="card-header">
        Is Subscribed
        </div>
        <div class="card-body">
            <?= $form->field($model, 'isSubscribed')->radio(['label' => 'True', 'value' => 1, 'uncheck' => null]) ?> 
            <?= $form->field($model, 'isSubscribed')->radio(['label' => 'False', 'value' => 0, 'uncheck' => null]) ?> 
        </div>
    </div>

    <br />

    <div class="card">
        <div class="card-header">
        Is Admin
        </div>
        <div class="card-body">
            <?= $form->field($model, 'isAdmin')->radio(['label' => 'True', 'value' => 1, 'uncheck' => null]) ?> 
            <?= $form->field($model, 'isAdmin')->radio(['label' => 'False', 'value' => 0, 'uncheck' => null]) ?> 
        </div>
    </div>

    <br />

    <?= $form->field($model, 'gameEventTimestamp')->textInput(['type' => 'datetime-local']) ?>
    <?= $form->field($model, 'gameEventNumber')->textInput(['type' => 'number']) ?>

    <br />

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <br />
    <hr />
    <p>Save your changes above before using the buttons below.</p>
    <hr />
    <br />

    <?php if (empty($model->hibernated)): ?>
    <div class="card text-white bg-dark">
        <div class="card-header">
            <b><i class="fa fa-cog"></i>&nbsp;Advanced Player Properties</b>
        </div>
        <div class="card-body">
            <b>Hibernate Player</b>
            <p>Hibernating this player will <em>only</em> hide their account in this campaign from others.  To revoke their access to this campaign, set all their roles above to <em>false</em>.</p>
            <?php $url = "/frontend/web/campaign-player"; ?>
            <?php $url .= "/hibernate?campaignId=".$campaignId; ?>
            <?php $url .= "&id=".$model->id; ?>
            <a href="<?= $url; ?>" class="btn btn-danger">Hibernate Player</a>
            <?php $characters = CampaignCharacter::find()->where(["playerId" => $model->id])->all(); ?>
            <?php if (!empty($characters)): ?>
                <hr />
                <b>Manage Characters</b>
                <p>This player is currently <b>Activated</b>, change <em>Hibernate</em> characters to <em>Active</em> if they are not already.</p>
                <?php foreach ($characters as $character): ?>
                    <?php $url = "/frontend/web/campaign-character/view"; ?>
                    <?php $url .= "?campaignId=" . $_GET['campaignId']; ?>
                    <?php $url .= "&id=" . $character->id; ?>
                    <?php $color = ($character->status > 2) ? "secondary" : "warning"; ?>
                    <a href="<?= $url; ?>" class="btn btn-<?= $color; ?>" style="margin:5px;">
                        <?= $character->name; ?> (<?= CampaignCharacter::status($character->status); ?>)
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="card-footer">
        </div>
    </div>
    <?php else: ?>
    <div class="card text-white bg-dark">
        <div class="card-header">
            <b><i class="fa fa-cog"></i>&nbsp;Advanced Player Properties</b>
        </div>
        <div class="card-body">
            <b>Activate Player</b>
            <p>Activating this player will <em>only</em> allow them to appear in this campaign for others.  To restore their access to this campaign, set the appropriate roles above to <em>true</em>.</p>
            <?php $url = "/frontend/web/campaign-player"; ?>
            <?php $url .= "/activate?campaignId=".$campaignId; ?>
            <?php $url .= "&id=".$model->id; ?>
            <a href="<?= $url; ?>" class="btn btn-primary">Activate Player</a>
            <?php $characters = CampaignCharacter::find()->where(["playerId" => $model->id])->all(); ?>
            <?php if (!empty($characters)): ?>
                <hr />
                <b>Manage Characters</b>
                <p>This player is currently <b>Hibernated</b>, change <em>New</em> and <em>Active</em> characters to <em>Hibernate</em> if they are not already.</p>
                <?php foreach ($characters as $character): ?>
                    <?php $url = "/frontend/web/campaign-character/view"; ?>
                    <?php $url .= "?campaignId=" . $_GET['campaignId']; ?>
                    <?php $url .= "&id=" . $character->id; ?>
                    <?php $color = ($character->status > 2) ? "secondary" : "warning"; ?>
                    <a href="<?= $url; ?>" class="btn btn-<?= $color; ?>" style="margin:5px;">
                        <?= $character->name; ?> (<?= CampaignCharacter::status($character->status); ?>)
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
        <div class="card-footer">
        </div>
    </div>
    <?php endif; ?>

</div>
