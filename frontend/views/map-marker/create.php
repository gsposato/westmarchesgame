<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\MapMarker $model */

$this->title = 'Create Map Marker';
$this->params['breadcrumbs'][] = ['label' => 'Map Markers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="map-marker-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
