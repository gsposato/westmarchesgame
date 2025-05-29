<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PlayerTrigger $model */

$this->title = 'Create Player Trigger';
$this->params['breadcrumbs'][] = ['label' => 'Player Triggers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="player-trigger-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
