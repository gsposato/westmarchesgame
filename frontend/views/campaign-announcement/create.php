<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CampaignAnnouncement $model */

$this->title = 'Create Campaign Announcement';
$this->params['breadcrumbs'][] = ['label' => 'Campaign Announcements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="campaign-announcement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
