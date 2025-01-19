<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

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
            'name',
            'gameInviteLink:ntext',
            'voiceVenueLink:ntext',
            'timeDuration',
            'levelRange',
            'goldPayoutPerPlayer',
            'baseBastionPointsPerPlayer',
            'bonusBastionPointsPerPlayer',
            'credit',
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
    <div id="gamecharacter">
        <?= $this->render('_character', [
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
    <div id="gamebonus">
        <?= $this->render('_bonus', [
            'model' => $model,
            'canModify' => $canModify
        ]) ?>
    </div>
</div>
<script type="text/javascript">
function copyText(id) {
  btn = "#"+id+"-btn";
  try {
      navigator.clipboard.writeText(document.getElementById(id).innerHtml);
      alert("Text Copied!");
      $(btn).removeClass("btn-primary");
      $(btn).addClass("btn-success");
      $(btn).html('<i class="fa fa-copy"></i>&nbsp;Copied!');
  } catch (err) {
      $(btn).removeClass("btn-primary");
      $(btn).addClass("btn-danger");
      $(btn).html('<i class="fa fa-copy"></i>&nbsp;Failed!');
      console.log(err);
  }
}
</script>
