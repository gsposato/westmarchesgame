<?php

use common\models\Equipment;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\EquipmentGoal $model */

$id = $_GET['campaignId'];
$update = "update?campaignId=".$id."&id=".$model->id; 
$delete = "delete?campaignId=".$id."&id=".$model->id; 
$create = "/equipment-goal-requirement/create?campaignId=".$id."&equipmentGoalId=".$model->id;
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Equipment Goals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$canModify = $model->canModify();
\yii\web\YiiAsset::register($this);
?>
<div class="equipment-goal-view">

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
                'label' => 'Equipment',
                'attribute' => 'equipmentId',
                'format' => 'raw',
                'value' => function($model) {
                    $equip = Equipment::findOne($model->equipmentId);
                    $campaignId = $_GET['campaignId'];
                    $id = $equip->id;
                    $href = "/frontend/web/equipment/view?campaignId=".$campaignId."&id=".$id;
                    if (!empty($equip->name)) {
                        return "<a href=".$href.">".$equip->name."</a>";
                    }
                    return "<a href=".$href.">".$model->equipmentId."</a>";
                },
            ],
            'name',
            'description:ntext',
        ],
        $model->view())
    ]) ?>

    <p>
        <?php $str = "<i class='fa fa-shield'></i>&nbsp;Create Goal Requirement"; ?>
        <?= Html::a($str, [$create], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            [
                'label' => 'Progress',
                'attribute' => 'progress',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->progress . "%";
                },
            ],
            'updated:datetime',
            [
                'label' => '',
                'attribute' => '',
                'format' => 'raw',
                'value' => function($model) {
                    $link = "/frontend/web/equipment-goal-requirement/view";
                    $link .= "?campaignId=" . $_GET['campaignId'];
                    $link .= "&id=" . $model->id; 
                    $html = '<a class="btn btn-primary" href="'.$link.'">Manage</a>';
                    return $html;
                },
            ],
        ],
    ]); ?>

</div>
