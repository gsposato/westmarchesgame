<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\MapMarker $model */

$id = $_GET['campaignId'];
$mapId = $_GET['mapId'];
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Map Markers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$canModify = $model->canModify();
\yii\web\YiiAsset::register($this);
?>
<div class="map-marker-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($canModify): ?>
        <?= Html::a('Update', ['update?campaignId='.$id.'&mapId='.$mapId.'&id='.$model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'id',
            'campaignId',
            'mapId',
            'name',
            'color',
            'lat',
            'lng',
        ],
        $model->view())
    ]) ?>

</div>
