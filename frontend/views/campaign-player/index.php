<?php

use common\models\CampaignPlayer;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Campaign Players';
$this->params['breadcrumbs'][] = $this->title;
$campaignId = $_GET['campaignId'];
$create = 'create?campaignId=' . $campaignId;
?>
<div class="campaign-player-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Campaign Player', [$create], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //'id',
            'name',
            'userId',
            /*
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
            */
            //'created',
            'updated:datetime',
            [
                'label' => '',
                'attribute' => '',
                'format' => 'raw',
                'value' => function($model) {
                    $link = "/frontend/web/campaign-player/view";
                    $link .= "?campaignId=" . $_GET['campaignId'];
                    $link .= "&id=" . $model->id; 
                    $html = '<a class="btn btn-primary" href="'.$link.'">Manage</a>';
                    return $html;
                },
            ],
        ],
    ]); ?>


</div>
