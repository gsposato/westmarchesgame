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
$create = "/ticket-comment/create?campaignId=".$id."&ticketId=".$model->id;
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
            [
                'label' => 'Note',
                'attribute' => 'note',
                'format' => 'raw',
                'value' => function($model) {
                    $json = json_decode($model->note);
                    if (!empty($json)) {
                        $html = "";
                        foreach ($json as $key => $value) {
                            $html .= "<b>".ucwords($key)."</b><br />";
                            $html .= $value . "<br /><br />";
                        }
                        return $html;
                    }
                    return $model->note;
                },
            ],
        ],
        $model->view())
    ]) ?>

    <p>
        <?php $str = "<i class='fa fa-comment'></i>&nbsp;Create Comment"; ?>
        <?= Html::a($str, [$create], ['class' => 'btn btn-success']) ?>
    </p>

    <?php foreach ($comments as $comment): ?>
        <?php $owner = User::findOne($comment->owner); ?>
        <br />
        <div class="card">
            <div class="card-header">
                <i class="fa fa-user">&nbsp;</i>&nbsp;<?= $owner->username ?? ""; ?>
            </div>
            <div class="card-body">
                <p><?= $comment->note; ?></p>
            </div>
            <div class="card-footer">
                <i class="fa fa-clock">&nbsp;</i>&nbsp;<?= date("m/d/Y h:i A", $comment->updated); ?>
            </div>
        </div>
    <?php endforeach; ?>

</div>
