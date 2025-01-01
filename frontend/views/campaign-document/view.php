<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\CampaignDocument $model */

$id = $_GET['campaignId'];
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Campaign Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="campaign-document-view">

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
        'attributes' => array_merge(
            [
            'id',
            'campaignId',
            'name',
            'url:ntext',
            [
                'label' => 'Visible to Players',
                'attribute' => 'playerVisible',
                'format' => 'text',
                'value' => function($model) {
                    if (empty($model->playerVisible)) {
                        return 'False';
                    }
                    return 'True';
                },
            ],
            [
                'label' => 'Visible to Hosts',
                'attribute' => 'hostVisible',
                'format' => 'text',
                'value' => function($model) {
                    if (empty($model->hostVisible)) {
                        return 'False';
                    }
                    return 'True';
                },
            ],
            ],
            $model->view()
        )
    ]) ?>

</div>
