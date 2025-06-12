<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\EquipmentGoal $model */

$this->title = 'Create Equipment Goal';
$this->params['breadcrumbs'][] = ['label' => 'Equipment Goals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equipment-goal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
