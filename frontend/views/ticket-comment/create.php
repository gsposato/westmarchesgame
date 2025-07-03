<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\TicketComment $model */

$this->title = 'Create Ticket Comment';
$this->params['breadcrumbs'][] = ['label' => 'Ticket Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-comment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
