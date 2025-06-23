<?php

use common\models\CampaignCharacter;
use common\models\Purchase;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Purchases';
$this->params['breadcrumbs'][] = $this->title;
$campaignId = $_GET['campaignId'];
$create = 'create?campaignId=' . $campaignId;
?>
<div class="purchase-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Purchase', [$create], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            [
                'label' => 'Character',
                'attribute' => 'characterId',
                'format' => 'text',
                'value' => function($model) {
                    $character = CampaignCharacter::findOne($model->characterId);
                    if (!empty($character->name)) {
                        return $character->name;
                    }
                    return $model->characterId;
                }
            ],
            'price',
            [
                'label' => 'Currency',
                'attribute' => 'currency',
                'format' => 'text',
                'value' => function($model) {
                    $currency = $model->currency();
                    if (!empty($currency[$model->currency])) {
                        return ucwords($currency[$model->currency]);
                    }
                    return $model->currency;
                }
            ],
            'updated:datetime',
            [
                'label' => '',
                'attribute' => '',
                'format' => 'raw',
                'value' => function($model) {
                    $link = "/frontend/web/purchase/view";
                    $link .= "?campaignId=" . $_GET['campaignId'];
                    $link .= "&id=" . $model->id; 
                    $html = '<a class="btn btn-primary" href="'.$link.'">Manage</a>';
                    return $html;
                },
            ],
        ],
    ]); ?>


</div>
