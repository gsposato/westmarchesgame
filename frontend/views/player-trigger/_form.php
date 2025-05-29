<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CampaignPlayer;
use common\models\User;

/** @var yii\web\View $this */
/** @var common\models\PlayerTrigger $model */
/** @var yii\widgets\ActiveForm $form */
$campaignPlayer = new CampaignPlayer();
$campaignPlayerSelect = $campaignPlayer->select();
$categorySelect = $model->getCategoryNames();
?>

<div class="player-trigger-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'playerId')->dropDownList($campaignPlayerSelect, ['prompt' => '']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category')->dropDownList($categorySelect, ['prompt' => '']) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

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
