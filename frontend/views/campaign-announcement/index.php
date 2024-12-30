<?php

use common\models\User;
use common\models\CampaignAnnouncement;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Campaign Announcements';
$this->params['breadcrumbs'][] = $this->title;
$campaignId = $_GET['campaignId'];
$create = 'create?campaignId=' . $campaignId;
?>
<div class="campaign-announcement-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Campaign Announcement', [$create], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'name',
            [
                'label' => 'Owner',
                'attribute' => 'owner',
                'format' => 'text',
                'value' => function($model) {
                    $user = User::findOne($model->owner);
                    return $user->username;
                },
            ],
            'updated:datetime',
            [
                'label' => '',
                'attribute' => '',
                'format' => 'raw',
                'value' => function($model) {
                    $link = "/frontend/web/campaign-announcement/view";
                    $link .= "?campaignId=" . $_GET['campaignId'];
                    $link .= "&id=" . $model->id; 
                    $html = '<a class="btn btn-primary" href="'.$link.'">Manage</a>';
                    return $html;
                },
            ],
        ],
    ]); ?>


</div>
