<?php

use common\models\Form;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Deleted Records';
$this->params['breadcrumbs'][] = $this->title;
$campaignId = $_GET['campaignId'];
$restore = '/frontend/web/deleted/restore?campaignId=' . $campaignId;
?>
<div class="form-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-warning">
        Showing <b><?= count($deletedRecords); ?></b> deleted records.
    </div>

    <?php foreach ($deletedRecords as $deletedRecord): ?>
        <div class="card">
            <div class="card-header">
                <b>Deleted Record</b>
                <?php $url = $restore.'&recordId='.$deletedRecord["id"]; ?>
                <?php $url .= '&tableName='.$deletedRecord["tableName"]; ?>
                <a href="<?= $url; ?>" class="btn btn-primary" style="float:right;">
                    <i class="fa fa-recycle"></i>&nbsp;Restore
                </a>
            </div>
            <div class="card-body">
                <pre style="overflow-x:hidden;color#fff !important;margin:0px;padding:15px;">
<?php print_r($deletedRecord); ?>
                </pre>
            </div>
        </div>
        <br />
    <?php endforeach; ?>

</div>
