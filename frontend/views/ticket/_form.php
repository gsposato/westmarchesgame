<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CampaignPlayer;
use common\models\Ticket;
use common\models\User;

/** @var yii\web\View $this */
/** @var common\models\Ticket $model */
/** @var yii\widgets\ActiveForm $form */
$campaignPlayer = new CampaignPlayer();
$campaignPlayerSelect = $campaignPlayer->select();
?>

<div class="ticket-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->dropDownList($campaignPlayerSelect, ['prompt' => '']); ?>

    <?php if (!Yii::$app->user->isGuest): ?>
        <?= $form->field($model, 'status')->dropDownList(Ticket::status(), ['prompt' =>'']); ?>
    <?php endif; ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <?php if (Yii::$app->user->isGuest): ?>
        <div class="form-group">
            <label class="control-label" for="campaigndocument-name">Campaign Name</label>
            <input type="text" id="challenge" class="form-control" name="challenge" />
        </div>
    <?php endif; ?>

    <?php if (!Yii::$app->user->isGuest && $model->canNotarize()): ?>
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
