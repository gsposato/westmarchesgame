<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CampaignPlayer $model */

$this->title = 'Create Campaign Player';
$this->params['breadcrumbs'][] = ['label' => 'Campaign Players', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="campaign-player-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
