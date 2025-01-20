<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CampaignPlayer;
use common\models\CampaignCharacter;

/** @var yii\web\View $this */
/** @var common\models\PlayerComplaint $model */

$id = $_GET['campaignId'];
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Player Complaints', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="player-complaint-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($model->canModify()): ?>
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
        'attributes' => array_merge (
            [
            'id',
            'gameId',
            'name',
            [
                'label' => 'Reporting Player',
                'attribute' => 'reportingPlayerId',
                'format' => 'text',
                'value' => function($model) {
                    $player = CampaignPlayer::findOne($model->reportingPlayerId);
                    if (!empty($player->name)) {
                        return $player->name;
                    }
                    return $model->reportingPlayerId;
                }
            ],
            [
                'label' => 'Reporting Character',
                'attribute' => 'reportingCharacterId',
                'format' => 'text',
                'value' => function($model) {
                    $character = CampaignCharacter::findOne($model->reportingCharacterId);
                    if (!empty($character->name)) {
                        return $character->name;
                    }
                    return $model->reportingCharacterId;
                }
            ],
            [
                'label' => 'Offending Player',
                'attribute' => 'offendingPlayerId',
                'format' => 'text',
                'value' => function($model) {
                    $player = CampaignPlayer::findOne($model->offendingPlayerId);
                    if (!empty($player->name)) {
                        return $player->name;
                    }
                    return $model->offendingPlayerId;
                }
            ],
            [
                'label' => 'Offending Character',
                'attribute' => 'offendingCharacterId',
                'format' => 'text',
                'value' => function($model) {
                    $character = CampaignCharacter::findOne($model->offendingCharacterId);
                    if (!empty($character->name)) {
                        return $character->name;
                    }
                    return $model->offendingCharacterId;
                }
            ],
            'note:ntext',
            ],
            $model->view()
        )
    ]) ?>

</div>
