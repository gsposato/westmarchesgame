<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\TicketComment $model */

$this->title = 'Update Ticket Comment: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ticket Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ticket-comment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
