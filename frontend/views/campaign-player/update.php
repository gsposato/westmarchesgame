<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CampaignPlayer $model */

$this->title = 'Update Campaign Player: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Campaign Players', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="campaign-player-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
