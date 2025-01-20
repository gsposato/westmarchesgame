<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Purchase;
use common\models\CampaignCharacter;

/** @var yii\web\View $this */
/** @var common\models\Purchase $model */
/** @var yii\widgets\ActiveForm $form */
$campaignCharacter = new CampaignCharacter();
$campaignCharacterSelect = $campaignCharacter->select();
?>

<div class="purchase-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'characterId')->dropDownList($campaignCharacterSelect, ['prompt' => '']) ?>

    <?= $form->field($model, 'currency')->dropDownList(Purchase::currency(), ['prompt' => '']) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <br />

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
