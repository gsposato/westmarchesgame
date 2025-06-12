<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\EquipmentGoal $model */

$this->title = 'Update Equipment Goal: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Equipment Goals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="equipment-goal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
