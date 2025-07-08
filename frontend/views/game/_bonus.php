<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\GameEvent;
use common\models\GamePlayer;
use common\models\CampaignPlayer;
use common\models\CampaignCharacter;

$id = $_GET['campaignId'];
$addRemoveBonus = '/frontend/web/game/bonus?campaignId=' . $id . '&id=' . $model->id;
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
                    <b>Game Bonus</b>
                </div>
                <div class="card-body">
                        <p>Choose the type of bonus characters receive for this game:</p>
                        <?php foreach ($gamePlayers as $gamePlayer): ?>
                            <?php $character = CampaignCharacter::findOne($gamePlayer->characterId); ?>
                            <?php if (empty($character)): ?>
                                <?php continue; ?>
                            <?php endif; ?>
                            <?php $ti = 'title="'.$character->description.'"'; ?>
                            <?php $url = $addRemoveBonus . "&characterId=" . $character->id; ?>
                            <?php $isHost = CampaignCharacter::isHostCharacter($model->id, $character->id); ?>
                            <?php $bonus = $gamePlayer->bonus($isHost, $view = true); ?>
                            <?php $alert = $bonus["alert"]; ?>
                            <?php $icon = $bonus["icon"]; ?>
                            <a href="<?= $url; ?>" class="btn btn-<?= $alert; ?>" style="margin:5px" <?= $ti; ?>>
                                <i class="fa <?= $icon; ?>"></i>&nbsp;<?= $character->name; ?>
                            </a>
                        <?php endforeach; ?>
                </div>
                <?php $bonuses = GamePlayer::bonuses(); ?>
                <div class="card-footer">
                    <small style="color:#888">
                        <?php foreach ($bonuses as $name => $details): ?>
                            <?php $icon = $details->icon; ?>
                            <i class="fa <?= $icon; ?>"></i>&nbsp;<?= $name; ?>&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php endforeach; ?>
                    </small>
                    <br />
                </div>
            </div>
            <br />
            <div class="card">
                <div class="card-body">
                    <ul>
                        <?php foreach ($bonuses as $name => $details): ?>
                            <li>
                                <b class="text-<?= $details->alert; ?>">
                                    <i class="fa <?= $details->icon; ?>"></i>&nbsp;<?= $name; ?>
                                </b>
                                <br />
                                <?php foreach ($details->roles as $key => $value): ?>
                                    <?php if ($value): ?>
                                        <span class="badge badge-<?= $details->alert; ?>"><?= $key; ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php foreach ($details->rewards as $key => $value): ?>
                                    <span class="badge badge-dark">
                                        <?php if ($value == 0): ?>
                                            no&nbsp;<?= $key; ?>
                                        <?php elseif ($value == 1): ?>
                                            game&nbsp;<?= $key; ?>
                                        <?php else: ?>
                                            <?= $value; ?>x&nbsp;game&nbsp;<?= $key; ?>
                                        <?php endif; ?>
                                    </span>
                                <?php endforeach; ?>
                            </li>
                            <br />
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
    <?php endif; ?>
<?php endif; ?>
