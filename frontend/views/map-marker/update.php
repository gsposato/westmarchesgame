<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\MapMarker $model */

$this->title = 'Update Map Marker: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Map Markers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="map-marker-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
