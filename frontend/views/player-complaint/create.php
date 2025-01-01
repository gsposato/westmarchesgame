<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PlayerComplaint $model */

$this->title = 'Create Player Complaint';
$this->params['breadcrumbs'][] = ['label' => 'Player Complaints', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="player-complaint-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
