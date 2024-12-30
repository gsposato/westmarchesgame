<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\PlayerComplaint $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="player-complaint-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gameId')->textInput() ?>

    <?= $form->field($model, 'reportingPlayerId')->dropDownList($playersMenu, ['prompt' => '']); ?>

    <?= $form->field($model, 'reportingCharacterId')->textInput() ?>

    <?= $form->field($model, 'offendingPlayerId')->dropDownList($playersMenu, ['prompt' => '']); ?>

    <?= $form->field($model, 'offendingCharacterId')->textInput() ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <br />

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
