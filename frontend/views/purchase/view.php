<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CampaignCharacter;

/** @var yii\web\View $this */
/** @var common\models\Purchase $model */

$id = $_GET['campaignId'];
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$canModify = $model->canModify();
\yii\web\YiiAsset::register($this);
?>
<div class="purchase-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
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
        'attributes' => array_merge(
            [
                'id',
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
                        if ($model->currency == 1) {
                            return "Gold";
                        }
                        if ($model->currency == 2) {
                            return "Bastion Points";
                        }
                        return $model->currency;
                    }
                ],
            ],
            $model->view()
        )
    ]) ?>

</div>
