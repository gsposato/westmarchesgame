<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Game $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="game-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <small>
        <p>
            <em>
                Pick something fun that hints at what this game will be about
            </em>
        </p>
    </small>

    <?= $form->field($model, 'gameInviteLink')->textInput() ?>
    <small>
        <p>
            <em>
                You can provide the Roll20 invite link for your game, helpful for new players
            </em>
        </p>
    </small>

    <?= $form->field($model, 'voiceVenueLink')->textInput() ?>
    <small>
        <p>
            <em>
                You can provide a direct link to the discord voice channel, helpful for new players
            </em>
        </p>
    </small>
    
    <?= $form->field($model, 'timeDuration')->textInput(['maxlength' => true]) ?>
    <small>
        <p>
            <em>
                Typically, it's 4-5 hours
            </em>
        </p>
    </small>

    <?= $form->field($model, 'levelRange')->textInput(['maxlength' => true]) ?>
    <small>
        <p>
            <em>
                Example: levels 3, 4, 5
            </em>
        </p>
    </small>

    <?= $form->field($model, 'goldPayoutPerPlayer')->textInput(["type" => "number"]) ?>
    <small>
        <p>
            <em>
                Typically, its 400
            </em>
        </p>
    </small>

    <?= $form->field($model, 'credit')->textInput(["type"=>"number"]) ?>
    <small>
        <p>
            <em>
                Credit represents the amount of game credit earned.  Typically, one game is worth one credit.
            </em>
        </p>
    </small>

    <br />

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
