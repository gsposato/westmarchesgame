<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Form $model */

$id = $_GET['campaignId'];
$create = "/map-marker/create?campaignId=".$id."&mapId=".$model->id;
$formUrl = "/frontend/web/form/form?campaignId=".$id."&id=".$model->id;
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$canModify = $model->canModify();
\yii\web\YiiAsset::register($this);
?>
<div class="form-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <a href="<?= $formUrl; ?>" class="btn btn-success" ><i class="fa fa-list-ul"></i>&nbsp;Form</a>
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
        'attributes' => array_merge([
            'id',
            'campaignId',
            'name',
            'status',
            'note:ntext',
        ],
        $model->view())
    ]) ?>

</div>
