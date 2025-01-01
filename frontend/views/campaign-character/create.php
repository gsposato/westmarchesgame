<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CampaignCharacter $model */

$this->title = 'Create Campaign Character';
$this->params['breadcrumbs'][] = ['label' => 'Campaign Characters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="campaign-character-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
