<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Equipment;
use common\models\CampaignCharacter;
use common\models\User;

/** @var yii\web\View $this */
/** @var common\models\Equipment $model */
/** @var yii\widgets\ActiveForm $form */
$campaignCharacter = new CampaignCharacter();
$campaignCharacterSelect = $campaignCharacter->select();
$categorySelect = Equipment::categorySelect();
$stateSelect = Equipment::stateSelect();
$userSelect = User::select();
?>

<div class="equipment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'characterId')->dropDownList($campaignCharacterSelect, ['prompt' => '']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category')->dropDownList($categorySelect, ['prompt' => '']) ?>

    <?= $form->field($model, 'state')->dropDownList($stateSelect, ['prompt' => '']) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php if ($model->canNotarize()): ?>
        <?= $form->field($model, 'owner')->dropDownList($userSelect, ['prompt' => '']); ?>
        <input type="hidden" name="notarizeKey" value="<?= $model->getNotarizeKey(); ?>" />
        <br />
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
