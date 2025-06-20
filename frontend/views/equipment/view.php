<?php

use common\models\CampaignCharacter;
use common\models\Equipment;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\Equipment $model */

$id = $_GET['campaignId'];
$update = "update?campaignId=".$id."&id=".$model->id; 
$delete = "delete?campaignId=".$id."&id=".$model->id; 
$create = "/equipment-goal/create?campaignId=".$id."&equipmentId=".$model->id;
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Equipments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$canModify = $model->canModify();
\yii\web\YiiAsset::register($this);
?>
<div class="equipment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($canModify): ?>
        <?= Html::a('Update', [$update], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', [$delete], [
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
            [
                'label' => 'Character',
                'attribute' => 'characterId',
                'format' => 'raw',
                'value' => function($model) {
                    $char = CampaignCharacter::findOne($model->characterId);
                    if (!empty($char->name)) {
                        return $char->name;
                    }
                    return $model->characterId;
                },
            ],
            'name',
            [
                'label' => 'Category',
                'attribute' => 'category',
                'format' => 'raw',
                'value' => function($model) {
                    $arr = Equipment::categorySelect();
                    if (!empty($arr[$model->category])) {
                        return $arr[$model->category];
                    }
                    return $model->category;
                },
            ],
            [
                'label' => 'State',
                'attribute' => 'state',
                'format' => 'raw',
                'value' => function($model) {
                    $arr = Equipment::stateSelect();
                    if (!empty($arr[$model->state])) {
                        return $arr[$model->state];
                    }
                    return $model->state;
                },
            ],
            'description:ntext',
        ],
        $model->view())
    ]) ?>

    <p>
        <?php $str = "<i class='fa fa-flag'></i>&nbsp;Create Goal"; ?>
        <?= Html::a($str, [$create], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            'updated:datetime',
            [
                'label' => '',
                'attribute' => '',
                'format' => 'raw',
                'value' => function($model) {
                    $link = "/frontend/web/equipment-goal/view";
                    $link .= "?campaignId=" . $_GET['campaignId'];
                    $link .= "&id=" . $model->id; 
                    $html = '<a class="btn btn-primary" href="'.$link.'">Manage</a>';
                    return $html;
                },
            ],
        ],
    ]); ?>

</div>
