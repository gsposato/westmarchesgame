<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\CampaignPlayer $model */

$id = $_GET['campaignId'];
$now = time();
$json = <<<JSON
{
    "campaignId": "{$id}",
    "campaignPlayerId": {$model->id},
    "unixtimestamp": {$now}
}
JSON;
$token = base64_encode($json);
$signup = (empty($_SERVER['HTTPS']) ? 'http' : 'https');
$signup .= "://$_SERVER[HTTP_HOST]/frontend/web/site/signup";
$invite = $signup . "?token=" . $token;
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Campaign Players', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="campaign-player-view">

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
        'attributes' => [
            'id',
            'campaignId',
            'name',
            'userId',
            [
                'label' => 'Is Player',
                'attribute' => 'isPlayer',
                'format' => 'text',
                'value' => function($model) {
                    if (empty($model->isPlayer)) {
                        return 'False';
                    }
                    return 'True';
                },
            ],
            [
                'label' => 'Is Host',
                'attribute' => 'isHost',
                'format' => 'text',
                'value' => function($model) {
                    if (empty($model->isHost)) {
                        return 'False';
                    }
                    return 'True';
                },
            ],
            [
                'label' => 'Is Support',
                'attribute' => 'isSupport',
                'format' => 'text',
                'value' => function($model) {
                    if (empty($model->isSupport)) {
                        return 'False';
                    }
                    return 'True';
                },
            ],
            [
                'label' => 'Is Subscribed',
                'attribute' => 'isSubscribed',
                'format' => 'text',
                'value' => function($model) {
                    if (empty($model->isSubscribed)) {
                        return 'False';
                    }
                    return 'True';
                },
            ],
            [
                'label' => 'Is Admin',
                'attribute' => 'isAdmin',
                'format' => 'text',
                'value' => function($model) {
                    if (empty($model->isAdmin)) {
                        return 'False';
                    }
                    return 'True';
                },
            ],
            [
                'label' => 'Is Hibernated',
                'attribute' => 'hibernated',
                'format' => 'text',
                'value' => function($model) {
                    if (empty($model->hibernated)) {
                        return 'False';
                    }
                    return 'True';
                },
            ],
            'gameEventTimestamp:datetime',
            'gameEventNumber',
            'created:datetime',
            'updated:datetime',
        ],
    ]) ?>

    <?php if (empty($model->userId) && empty($model->hibernated)): ?>
    <div class="card">
        <div class="card-header">
            <b>Invite Link</b>
            <button id="invite-text-btn" onclick="copyText('invite-text')" class="btn btn-primary" style="float:right;">
                <i class="fa fa-copy"></i>&nbsp;Copy
            </button>
        </div>
        <div class="card-body" style="background-color:#333;color:#fff">
<pre id="invite-text" style="overflow-x:hidden;">
<?= $invite; ?>
</pre>
        </div>
        <div class="card-footer">
            &nbsp;
        </div>
    </div>
    <?php endif; ?>



</div>
