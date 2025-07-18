<?php

use common\models\TicketComment;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Ticket Comments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-comment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Ticket Comment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'campaignId',
            'ticketId',
            'note:ntext',
            'owner',
            //'creator',
            //'created',
            //'updated',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, TicketComment $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
