<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\GameEvent;
use common\models\GamePlayer;
use common\models\CampaignPlayer;
use common\models\CampaignCharacter;

$id = $_GET['campaignId'];
$addRemoveCharacter = '/frontend/web/game/character?campaignId=' . $id . '&id=' . $model->id;
$gameEvent = GameEvent::find()->where(["gameId" => $model->id])->one();
$gamePlayers = array();
if (!empty($gameEvent)) {
    $gamePlayers = GamePlayer::organize($model->id);
}
?>

    <?php if ($canModify): ?>
        <?php if (!empty($gamePlayers)): ?>
            <div class="card">
                <div class="card-header">
                    <b>Game Character</b>
                </div>
                <div class="card-body">
                        <p>Choose the characters receiving credit for this game:</p>
                        <?php foreach ($gamePlayers as $gamePlayer): ?>
                            <?php $where = ["playerId" => $gamePlayer->userId, "status" => 2]; ?>
                            <?php $characters = CampaignCharacter::find()->where($where)->all(); ?>
                                <?php foreach ($characters as $character): ?>
                                <?php $url = $addRemoveCharacter . "&characterId=" . $character->id; ?>
                                <?php if ($gamePlayer->characterId == $character->id): ?>
                                    <a href="<?= $url; ?>" class="btn btn-success" style="margin:5px">
                                        <i class="fa fa-check"></i>&nbsp;<?= $character->name; ?>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= $url; ?>" class="btn btn-secondary" style="margin:5px">
                                        <i class="fa fa-minus"></i>&nbsp;<?= $character->name; ?>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                </div>
            </div>
    <?php endif; ?>
<?php endif; ?>
