<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\EquipmentGoalRequirement $model */

$this->title = 'Create Equipment Goal Requirement';
$this->params['breadcrumbs'][] = ['label' => 'Equipment Goal Requirements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equipment-goal-requirement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
