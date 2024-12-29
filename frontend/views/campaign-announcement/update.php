<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CampaignAnnouncement $model */

$this->title = 'Update Campaign Announcement: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Campaign Announcements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="campaign-announcement-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
