<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Campaign;
use common\models\Game;
use common\models\GamePlayer;
use common\models\User;

/** @var yii\web\View $this */
/** @var common\models\Game $model */

$id = $_GET['campaignId'];
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Games', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$canModify = $model->canModify();
\yii\web\YiiAsset::register($this);
?>
<div class="game-view">

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
            'campaignId',
            [
                'label' => 'Session ID',
                'attribute' => 'id',
                'format' => 'raw',
                'value' => function($model) {
                    return Game::session($model->id);
                },
            ],
            'name',
            [
                'label' => 'Category',
                'attribute' => 'category',
                'format' => 'raw',
                'value' => function($model) {
                    $campaign = Campaign::findOne($_GET['campaignId']);
                    $rules = json_decode($campaign->rules);
                    return $model->categories($rules, "name", $model->category);
                },
            ],
            [
                'label' => 'Event Timestamp',
                'attribute' => '',
                'format' => 'raw',
                'value' => function($model) {
                    return game::event($model->id);
                },
            ],
            'gameInviteLink:ntext',
            'voiceVenueLink:ntext',
            'timeDuration',
            'levelRange',
            'goldPayoutPerPlayer',
            'baseBastionPointsPerPlayer',
            'bonusBastionPointsPerPlayer',
            'credit',
            [
                'attribute' => 'host',
                'value' => function ($model) {
                    $user = User::findOne($model->host);
                    if (!empty($user->username)) {
                        return $user->username;
                    }
                    return $model->host;
                },
            ],
        ],
        $model->view()
        )
    ]) ?>
    <div id="gamepoll">
        <?= $this->render('_poll', [
            'model' => $model,
            'canModify' => $canModify
        ]) ?>
    </div>
    <br />
    <div id="gameevent">
        <?= $this->render('_event', [
            'model' => $model,
            'canModify' => $canModify
        ]) ?>
    </div>
    <br />
    <div id="gametrigger">
        <?= $this->render('_trigger', [
            'model' => $model,
            'canModify' => $canModify
        ]) ?>
    </div>
    <br />
    <div id="gamecharacter">
        <?= $this->render('_character', [
            'model' => $model,
            'canModify' => $canModify
        ]) ?>
    </div>
    <br />
    <div id="gameequipment">
        <?= $this->render('_equipment', [
            'model' => $model,
            'canModify' => $canModify
        ]) ?>
    </div>
    <br />
    <div id="gamesummary">
        <?= $this->render('_summary', [
            'model' => $model,
            'canModify' => $canModify
        ]) ?>
    </div>
    <br />
    <?php if (!empty(GamePlayer::bonuses())): ?>
    <div id="gameroundupnote">
        <?= $this->render('_roundupnote', [
            'model' => $model,
            'canModify' => $canModify
        ]) ?>
    </div>
    <?php endif; ?>
    <br />
    <?php if (!empty(GamePlayer::bonuses())): ?>
    <div id="gamebonus">
        <?= $this->render('_bonus', [
            'model' => $model,
            'canModify' => $canModify
        ]) ?>
    </div>
    <?php endif; ?>
</div>
