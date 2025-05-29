<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PlayerTrigger $model */

$this->title = 'Update Player Trigger: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Player Triggers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="player-trigger-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
