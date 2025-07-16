<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Form $model */

$id = $_GET['campaignId'];
$create = "/map-marker/create?campaignId=".$id."&mapId=".$model->id;
$formUrl = "/frontend/web/form/form?campaignId=".$id."&id=".$model->id;
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$canModify = $model->canModify();
$form = json_decode($model->note);
\yii\web\YiiAsset::register($this);
?>
<div class="form-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <hr />
    <?php if (!empty($form)): ?>
        <?php $activeform = ActiveForm::begin(); ?>
        <?php foreach ($form as $name => $field): ?>
            <div class="form-group">
                <label for="<?= $name; ?>"><?= $field->label; ?></label>
                <?php if (empty($field->type)): ?>
                    <?php continue; ?>
                <?php endif; ?>
                <?php if ($field->type != "textarea"): ?>
                    <input 
                        name="<?= $name; ?>"
                        type="<?= $field->type; ?>"
                        value="<?= $field->value ?? ''; ?>"
                        placeholder="<?= $field->placeholder ?? ''; ?>"
                        aria-required="<?= $field->ariarequired ?? ''; ?>"
                        aria-invalid="<?= $field->ariainvalid ?? ''; ?>"
                        class="form-control" 
                    />
                <?php else: ?>
                    <textarea 
                        name="<?= $name; ?>"
                        type="<?= $field->type; ?>"
                        value="<?= $field->value ?? ''; ?>"
                        placeholder="<?= $field->placeholder ?? ''; ?>"
                        aria-required="<?= $field->ariarequired ?? ''; ?>"
                        aria-invalid="<?= $field->ariainvalid ?? ''; ?>"
                        class="form-control" 
                        rows="<?= $field->rows ?? ''; ?>"
                    ></textarea>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <?php if (Yii::$app->user->isGuest): ?>
            <div class="form-group">
                <label class="control-label" for="campaigndocument-name">Campaign Name</label>
                <input type="text" id="challenge" class="form-control" name="challenge" />
            </div>
        <?php endif; ?>
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>
