<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\User;

/** @var yii\web\View $this */
/** @var common\models\CampaignPlayer $model */

$this->title = 'Update Campaign Player: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Campaign Players', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="campaign-player-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="campaign-player-form">

    <?php $form = ActiveForm::begin(); ?>

    <br />

    <div class="form-group">
        <label for="email">Email:</label>
        <input id="email" type="text" class="form-control" disabled="disabled" value="<?= Yii::$app->user->identity->email; ?>" />
        <small style="color:red;">You will need a system administrator to change this.</small>
    </div>

    <br />

    <div class="card">
        <div class="card-header">
        Is Subscribed
        </div>
        <div class="card-body">
            <?= $form->field($model, 'isSubscribed')->radio(['label' => 'True', 'value' => 1, 'uncheck' => null]) ?> 
            <?= $form->field($model, 'isSubscribed')->radio(['label' => 'False', 'value' => 0, 'uncheck' => null]) ?> 
        </div>
    </div>

    <br />

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    </div>

</div>
