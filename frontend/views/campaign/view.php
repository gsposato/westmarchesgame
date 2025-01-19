<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Campaign $model */

$this->title = 'Campaign Rules';//$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Campaigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="campaign-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($model->canModify()): ?>
        <?= Html::a('Update Campaign', ['update', 'campaignId' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete Entire Campaign!', ['delete', 'campaignId' => $model->id], [
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
                'name',
            ],
            $model->view()
        )
    ]) ?>

    <div class="card">
        <div class="card-header">
            <b>Campaign Rules</b>
        </div>
    <div class="card-body" style="background-color:#333;color:#fff">
<pre id="rules-text" style="overflow-x:hidden;">
<code>
<?php print_r(json_decode($model->rules, true)); ?>
</code>
</pre>
        </div>
        <div class="card-footer">
            &nbsp;
        </div>
    </div>

</div>
