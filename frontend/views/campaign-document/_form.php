<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\CampaignDocument $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="campaign-document-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textarea(['rows' => 6]) ?>

    <br />

    <div class="card">
        <div class="card-header">
        Visible to Players
        <?php $attr = 'playerVisible'; ?>
        </div>
        <div class="card-body">
            <?= $form->field($model, $attr)->radio(['label' => 'True', 'value' => 1, 'uncheck' => null]) ?> 
            <?= $form->field($model, $attr)->radio(['label' => 'False', 'value' => 0, 'uncheck' => null]) ?> 
        </div>
    </div>

    <br />

    <div class="card">
        <div class="card-header">
        Visible to Hosts
        <?php $attr = 'hostVisible'; ?>
        </div>
        <div class="card-body">
            <?= $form->field($model, $attr)->radio(['label' => 'True', 'value' => 1, 'uncheck' => null]) ?> 
            <?= $form->field($model, $attr)->radio(['label' => 'False', 'value' => 0, 'uncheck' => null]) ?> 
        </div>
    </div>

    <br />

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
