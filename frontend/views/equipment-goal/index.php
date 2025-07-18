<?php

use common\models\EquipmentGoal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Equipment Goals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equipment-goal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Equipment Goal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'campaignId',
            'equipmentId',
            'name',
            'description:ntext',
            //'owner',
            //'creator',
            //'created',
            //'updated',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, EquipmentGoal $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
