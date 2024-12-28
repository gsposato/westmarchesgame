<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Campaign $model */
/** @var yii\widgets\ActiveForm $form */
?>
<div class="campaign-form">

    <?php $form = ActiveForm::begin(['id' => 'my-campaign-form']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rules')->textarea(['rows' => 6]) ?>

    <br />
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
