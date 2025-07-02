<?php

use common\models\CampaignPlayer;
use common\models\Ticket;
use common\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Ticket $model */

$id = $_GET['campaignId'];
$update = "update?campaignId=".$id."&id=".$model->id; 
$delete = "delete?campaignId=".$id."&id=".$model->id; 
$this->title = "Ticket #".$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$canModify = $model->canModify();
\yii\web\YiiAsset::register($this);
?>
<div class="ticket-view">

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
            'campaignId',
            [
                'label' => 'Name',
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($model) {
                    $player = CampaignPlayer::findOne($model->name);
                    if (!empty($player->name)) {
                        return $player->name;
                    }
                    return $model->name;
                },
            ],
            [
                'label' => 'Status',
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model) {
                    $status = Ticket::status();
                    $style = Ticket::style();
                    $html = "<span class='badge badge-".$style[$model->status]."'>";
                    $html .= $status[$model->status];
                    $html.= "</span>";
                    return $html;
                },
            ],
            'note:ntext',
        ],
        $model->view())
    ]) ?>

    <?php foreach ($comments as $comment): ?>
        <br />
        <div class="card">
            <div class="card-header">
                <i class="fa fa-comment">&nbsp;</i>
            </div>
            <div class="card-body">
                <p><?= $comment->note; ?></p>
            </div>
        </div>
    <?php endforeach; ?>

</div>
