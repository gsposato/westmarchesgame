<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CampaignCharacter;
use common\models\CampaignPlayer;
use common\models\User;

/** @var yii\web\View $this */
/** @var common\models\CampaignCharacter $model */
/** @var yii\widgets\ActiveForm $form */
$campaignPlayer = new CampaignPlayer();
$campaignPlayerSelect = $campaignPlayer->select();
?>

<div class="campaign-character-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'playerId')->dropDownList($campaignPlayerSelect, ['prompt' => '']) ?>

    <?= $form->field($model, 'type')->dropDownList(CampaignCharacter::type(), ['prompt' => '']) ?>

    <?= $form->field($model, 'status')->dropDownList(CampaignCharacter::status(), ['prompt' => '']) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'bastionName')->textInput() ?>

    <?= $form->field($model, 'bastionType')->textInput() ?>

    <?= $form->field($model, 'startingGold')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'startingBastionPoints')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'startingCredit')->textInput(['type' => 'number']) ?>

    <?php if (empty($model->firstGamePlayed)): ?>
        <?= $form->field($model, 'firstGamePlayed')->textInput(['type' => 'datetime-local']) ?>
    <?php else: ?>
        <?php $firstGamePlayed = date('m/d/y H:i A', $model->firstGamePlayed); ?>
        <?= $form->field($model, 'firstGamePlayed')->textInput(['value' => $firstGamePlayed]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'extra')->textInput() ?>

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
