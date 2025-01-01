<?php

use common\models\CampaignDocument;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Campaign Documents';
$this->params['breadcrumbs'][] = $this->title;
$campaignId = $_GET['campaignId'];
$create = 'create?campaignId=' . $campaignId;
?>
<div class="campaign-document-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Campaign Document', [$create], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'label' => 'Name',
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($model) {
                    $link = '<a href="'.$model->url.'">';
                    $link .= $model->name;
                    $link .= '</a>';
                    return $link;
                }
            ],
            //'url:ntext',
            //'playerVisible',
            //'hostVisible',
            //'owner',
            //'creator',
            //'created',
            'updated:datetime',
            [
                'label' => '',
                'attribute' => '',
                'format' => 'raw',
                'value' => function($model) {
                    $link = "/frontend/web/campaign-document/view";
                    $link .= "?campaignId=" . $_GET['campaignId'];
                    $link .= "&id=" . $model->id; 
                    $html = '<a class="btn btn-primary" href="'.$link.'">Manage</a>';
                    return $html;
                },
            ],
        ],
    ]); ?>


</div>
