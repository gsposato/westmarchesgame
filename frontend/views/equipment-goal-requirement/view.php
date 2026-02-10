<?php

use common\models\EquipmentGoal;
use common\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\EquipmentGoalRequirement $model */

$id = $_GET['campaignId'];
$update = "update?campaignId=".$id."&id=".$model->id; 
$delete = "delete?campaignId=".$id."&id=".$model->id; 
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Equipment Goal Requirements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$canModify = $model->canModify();
\yii\web\YiiAsset::register($this);
?>
<div class="equipment-goal-requirement-view">

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
                'label' => 'Equipment Goal',
                'attribute' => 'equipmentGoalId',
                'format' => 'raw',
                'value' => function($model) {
                    $goal = EquipmentGoal::findOne($model->equipmentGoalId);
                    $campaignId = $_GET['campaignId'];
                    $id = $goal->id;
                    $href = "/frontend/web/equipment-goal/view?campaignId=".$campaignId."&id=".$id;
                    if (!empty($goal->name)) {
                        return "<a href=".$href.">".$goal->name."</a>";
                    }
                    return "<a href=".$href.">".$model->equipmentId."</a>";
                },
            ],
            'name',
            'description:ntext',
            [
                'label' => 'Progress',
                'attribute' => 'progress',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->progress . "%";
                },
            ],
        ],
        $model->view())
    ]) ?>

    <br />
    <br />

    <h3>Events</h3>

    <hr />

    <?php foreach ($events as $event): ?>
        <?php $name = $event->attributeName; ?>
        <?php $value = $event->attributeValue; ?>
        <?php $time = date("m/d/Y h:i A", $event->created); ?>
        <?php $owner = User::findOne($event->owner); ?>
        <div class="card">
            <div class="card-header">
                <i class="fa fa-user">&nbsp;</i>&nbsp;
                <?= $owner->username ?? ""; ?>
            </div>
            <div class="card-body">
                <b><?= ucwords($name); ?>:</b> <?= $value; ?>
            </div>
            <div class="card-footer">
                <i class="fa fa-clock"></i>&nbsp;<?= $time; ?>
            </div>
        </div>
    <?php endforeach; ?>


</div>
