<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\EquipmentGoalRequirement $model */

$this->title = 'Update Equipment Goal Requirement: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Equipment Goal Requirements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="equipment-goal-requirement-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
