<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CampaignCharacter;
use common\models\GamePlayer;
use common\models\Game;

/** @var yii\web\View $this */
/** @var common\models\CampaignCharacter $model */

$id = $_GET['campaignId'];
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Campaign Characters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$gamesPlayed = GamePlayer::find()->where(["characterId" => $model->id])->all();
$characterAdvancement = CampaignCharacter::advancement($id, $gamesPlayed);
\yii\web\YiiAsset::register($this);
?>
<div class="campaign-character-view">

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
        'attributes' => array_merge(
            [
            'id',
            'name',
            [
                'label' => 'Type',
                'attribute' => 'type',
                'format' => 'text',
                'value' => function($model) {
                    $type = CampaignCharacter::type();
                    if (!empty($type[$model->type])) {
                        return $type[$model->type];
                    }
                    return $model->type;
                }
            ],
            [
                'label' => 'Status',
                'attribute' => 'status',
                'format' => 'text',
                'value' => function($model) {
                    $type = CampaignCharacter::status();
                    if (!empty($type[$model->status])) {
                        return $type[$model->status];
                    }
                    return $model->status;
                }
            ],
            'description:ntext',
            ],
            $model->view()
        )
    ]) ?>

</div>


            <div class="card">
                <div class="card-header">
                    <b>Game History</b>
                    <b style="float: right;">
                        <button class="btn btn-secondary">
                            Character Level: <?= $characterAdvancement; ?>
                        </button>
                    </b>
                </div>
                <div class="card-body">
                <ol>
                <?php $totalGoldEarned = 0; ?>
                <?php foreach ($gamesPlayed as $gamePlayed): ?>
                    <?php $game = Game::findOne($gamePlayed->gameId); ?>
                    <?php if (!$game->isEnded()): ?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <li>
                        Session #<?= $game->id ?> - <?= $game->name; ?> /
                        <small style="font-weight:bold;">
                            <?= $game->credit; ?> credit<?= $game->credit == 1 ? "" : "s"; ?>
                        </small> /
                        <small style="font-weight:bold;color:#df8607;">
                            <?= $game->goldPayoutPerPlayer; ?> gold
                            <?php $totalGoldEarned += $game->goldPayoutPerPlayer; ?>
                        </small>
                    </li>
                <?php endforeach; ?>
                </ol>
                </div>
                <div class="card-footer">
                    <?php if ($totalGoldEarned > 0): ?>
                        Total Gold Earned: <b style="color:#df8607"><?= $totalGoldEarned; ?></b>
                    <?php else: ?>
                        Total Gold Earned: <b><?= $totalGoldEarned; ?></b>
                    <?php endif; ?>
                </div>
            </div>
