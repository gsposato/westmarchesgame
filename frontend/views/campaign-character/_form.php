<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CampaignCharacter;
use common\models\CampaignPlayer;

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

    <br />

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
