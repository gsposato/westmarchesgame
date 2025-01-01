<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CampaignDocument $model */

$this->title = 'Create Campaign Document';
$this->params['breadcrumbs'][] = ['label' => 'Campaign Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="campaign-document-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
