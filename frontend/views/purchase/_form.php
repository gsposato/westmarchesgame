<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Game;
use common\models\Purchase;
use common\models\CampaignCharacter;
use common\models\User;

/** @var yii\web\View $this */
/** @var common\models\Purchase $model */
/** @var yii\widgets\ActiveForm $form */
$game = new Game();
$gameSelect = $game->select();
$campaignCharacter = new CampaignCharacter();
$campaignCharacterSelect = $campaignCharacter->select();
?>

<div class="purchase-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'characterId')->dropDownList($campaignCharacterSelect, ['prompt' => '']) ?>

        <br/ >
        <div class="alert alert-warning">
            If this purchase was awarded <b>freely</b> in a game, choose the game here.  <b>Otherwise leave blank</b>.
        </div>

    <?= $form->field($model, 'gameId')->dropDownList($gameSelect, ['prompt' => '']) ?>

    <?= $form->field($model, 'currency')->dropDownList(Purchase::currency(), ['prompt' => '']) ?>

    <?= $form->field($model, 'price')->textInput() ?>

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
