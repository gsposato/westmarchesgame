<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\CampaignPlayer $model */

$id = $_GET['campaignId'];
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Campaign Players', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="campaign-player-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($model->canModify()): ?>
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
        'attributes' => [
            'id',
            'campaignId',
            'name',
            'userId',
            [
                'label' => 'Is Player',
                'attribute' => 'isPlayer',
                'format' => 'text',
                'value' => function($model) {
                    if (empty($model->isPlayer)) {
                        return 'False';
                    }
                    return 'True';
                },
            ],
            [
                'label' => 'Is Host',
                'attribute' => 'isHost',
                'format' => 'text',
                'value' => function($model) {
                    if (empty($model->isHost)) {
                        return 'False';
                    }
                    return 'True';
                },
            ],
            [
                'label' => 'Is Admin',
                'attribute' => 'isAdmin',
                'format' => 'text',
                'value' => function($model) {
                    if (empty($model->isAdmin)) {
                        return 'False';
                    }
                    return 'True';
                },
            ],
            'created:datetime',
            'updated:datetime',
        ],
    ]) ?>

</div>
