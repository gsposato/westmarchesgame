<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

/** @var yii\web\View $this */
/** @var common\models\Map $model */
/** @var yii\widgets\ActiveForm $form */
$userSelect = User::select();
?>

<div class="map-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <br />

    <div class="alert alert-warning">
        Upload your image with a service like <a href="https://postimages.org">Post Images</a> and put the <b>URL</b> that links directly to the image into the <b>Image field</b> below.  For best results, the aspect ratio of the image should be 1.6.  The reccomended minimum size should be <b>1,600 pixels width by 1,000 pixels height</b>. The DPI should be 72.
    </div>

    <?= $form->field($model, 'image')->textarea(['rows' => 6]) ?>

    <br />
    <div class="alert alert-primary">
        <b>Min Zoom</b> is the total amount of times the map will shrink when zoom gestures are used.  Typically, its 0.
    </div>
    <?= $form->field($model, 'minzoom')->textInput(['maxlength' => true]) ?>

    <br />
    <div class="alert alert-primary">
        <b>Max Zoom</b> is the total amount of times the map will enlarge when zoom gestures are used.  Typically, its 3.
    </div>
    <?= $form->field($model, 'maxzoom')->textInput(['maxlength' => true]) ?>

    <br />
    <div class="alert alert-primary">
        <b>Default Zoom</b> is the amount of times the map is already zoomed in before a zoom gesture is used.  Typically, it's 0.
    </div>
    <?= $form->field($model, 'defaultzoom')->textInput(['maxlength' => true]) ?>

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
