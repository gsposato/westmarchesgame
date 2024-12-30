<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\CampaignPlayer $model */
/** @var yii\widgets\ActiveForm $form */
$campaignId = $_GET['campaignId'];
?>

<div class="campaign-player-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'userId')->textInput() ?>

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
        Is Admin
        </div>
        <div class="card-body">
            <?= $form->field($model, 'isAdmin')->radio(['label' => 'True', 'value' => 1, 'uncheck' => null]) ?> 
            <?= $form->field($model, 'isAdmin')->radio(['label' => 'False', 'value' => 0, 'uncheck' => null]) ?> 
        </div>
    </div>

    <br />

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
