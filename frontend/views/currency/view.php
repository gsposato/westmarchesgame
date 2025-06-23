<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Currency $model */

$id = $_GET['campaignId'];
$update = "update?campaignId=".$id."&id=".$model->id; 
$delete = "delete?campaignId=".$id."&id=".$model->id; 
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Currencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="currency-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', [$update], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', [$delete], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'id',
            'campaignId',
            'name',
            'color',
            'description:ntext',
        ],
        $model->view())
    ]) ?>

</div>
