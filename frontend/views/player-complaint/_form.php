<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CampaignPlayer;
use common\models\CampaignCharacter;

/** @var yii\web\View $this */
/** @var common\models\PlayerComplaint $model */
/** @var yii\widgets\ActiveForm $form */
$campaignPlayer = new CampaignPlayer();
$campaignPlayerSelect = $campaignPlayer->select();
$campaignCharacter = new CampaignCharacter();
$campaignCharacterSelect = $campaignCharacter->select();
?>

<div class="player-complaint-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gameId')->textInput() ?>

    <?= $form->field($model, 'reportingPlayerId')->dropDownList($campaignPlayerSelect, ['prompt' => '']); ?>

    <?= $form->field($model, 'reportingCharacterId')->dropDownList($campaignCharacterSelect, ['prompt' => '']) ?>

    <?= $form->field($model, 'offendingPlayerId')->dropDownList($campaignPlayerSelect, ['prompt' => '']); ?>

    <?= $form->field($model, 'offendingCharacterId')->dropDownList($campaignCharacterSelect, ['prompt' => '']) ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <br />

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
