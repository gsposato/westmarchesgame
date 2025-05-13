<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\Map $model */

$id = $_GET['campaignId'];
$mapUrl = "/frontend/web/map/map?campaignId=".$id."&id=".$model->id;
$create = "/map-marker/create?campaignId=".$id."&mapId=".$model->id;
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Maps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$canModify = $model->canModify();
\yii\web\YiiAsset::register($this);
?>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <a href="<?= $mapUrl; ?>" class="btn btn-success" ><i class="fa fa-map"></i>&nbsp;Map</a>
        <?php if ($canModify): ?>
        <?= Html::a('Update', ['update?campaignId='.$id.'&id='.$model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete?campaignId='.$id.'&id='.$model->id], [
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
            'name',
            'image:ntext',
            'minzoom',
            'maxzoom',
            'defaultzoom',
        ],
        $model->view())
    ]) ?>

<p>
        <?php $str = "<i class='fa fa-map-marker'></i>&nbsp;Create Map Marker"; ?>
        <?= Html::a($str, [$create], ['class' => 'btn btn-success']) ?>
</p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'name',
            'updated:datetime',
            [
                'label' => '',
                'attribute' => '',
                'format' => 'raw',
                'value' => function($model) {
                    $link = "/frontend/web/map-marker/view";
                    $link .= "?campaignId=" . $_GET['campaignId'];
                    $link .= "&mapId=" . $_GET['id']; 
                    $link .= "&id=" . $model->id; 
                    $html = '<a class="btn btn-primary" href="'.$link.'">Manage</a>';
                    return $html;
                },
            ],
        ],
    ]); ?>
