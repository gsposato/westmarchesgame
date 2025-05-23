<?php

use common\models\Game;
use common\models\GamePoll;
use common\models\GameNote;
use common\models\GamePlayer;
use common\models\CampaignPlayer;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Games Roundup';
$this->params['breadcrumbs'][] = $this->title;
$campaignId = $_GET['campaignId'];
$create = 'create?campaignId=' . $campaignId;
$roundup = 'roundup?campaignId=' . $campaignId;
$after = $_GET["after"] ?? "";
$before = $_GET["before"] ?? "";
?>
<div class="game-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="card">
        <div class="card-header">
            <b>Date Range</b>
        </div>
        <div class="card-body">
            <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="GET">
                <label for="from">From:</label>
                <input class="form-control" type="datetime-local" id="from" name="after" value="<?= $after; ?>">
                <br />
                <label for="to">To:</label>
                <input class="form-control" type="datetime-local" id="to" name="before" value="<?= $before; ?>">
                <br />
                <input type="hidden" name="campaignId" value="<?= $campaignId; ?>" />
                <button class="btn btn-secondary" type="submit">Roundup</button>
            </form>
        </div>
        <div class="card-footer">
            &nbsp;
        </div>
    </div>

    <br />

    <div class="card">
        <div class="card-header">
            <b>Results</b>
            <button id="roundup-text-btn" onclick="copyText('roundup-text')" class="btn btn-primary" style="float:right;">
                <i class="fa fa-copy"></i>&nbsp;Copy
            </button>
        </div>
        <div class="card-body" style="background-color:#333;color:#fff">
<pre id="roundup-text" style="overflow-x:hidden;">
<?php foreach ($games as $game): ?>
<?php $sessionId = Game::session($game->id); ?>
<?= $sessionId; ?>. <?= $game->name; ?> 
<?php $owner = CampaignPlayer::find()->where(["userId" => $game->host()])->one(); ?>
**DM** <?= ucfirst($owner->name); ?> 
<?php $gamePlayers = array(); ?>
<?php $gamePlayers = GamePlayer::organize($game->id); ?>
<?php if (!empty($gamePlayers)): ?>
<?php foreach ($gamePlayers as $gamePlayer): ?>
<?php if ($gamePlayer->status == GamePlayer::STATUS_COHOST): ?>
<?php if (!empty($gamePlayer->characterId)): ?>
<?php endif; ?>
*CoDM* <?= ucfirst($gamePlayer->name()); ?> 
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
<?php $gamePoll = GamePoll::find()->where(["gameId" => $game->id])->one(); ?>
<?= $gamePoll->note; ?> 
<?php $gameNotes = GameNote::find()->where(["gameId" => $game->id])->andWhere(["inGameSummary" => 2])->all(); ?>
Highlights: 
<?php foreach ($gameNotes as $gameNote): ?>
<?= $gameNote->note; ?>  
<?php endforeach; ?> 
<?php endforeach; ?> 

<?php if (!empty($levels)): ?>
**Current Level of PCs**
<?php foreach ($levels as $key => $value) :?>
Total Level <?= $key; ?> PCs = <?= $value; ?> 
<?php endforeach; ?>
<?php endif; ?>

**New PCs**
<?php foreach ($new as $character): ?>
<?= $character->name; ?> 
<?php endforeach; ?>

**Retired PCs**
<?php foreach ($retired as $character): ?>
<?= $character->name; ?> 
<?php endforeach; ?>
</pre>
        </div>
        <div class="card-footer">
            &nbsp;
        </div>
    </div>
</div>
