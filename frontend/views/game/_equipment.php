<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\GameEvent;
use common\models\GamePlayer;
use common\models\CampaignPlayer;
use common\models\CampaignCharacter;
use common\models\Equipment;
use common\models\EquipmentGoal;
use common\models\EquipmentGoalRequirement as Egr;

$id = $_GET['campaignId'];
$state = Equipment::stateSelect();
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
                    <b>Game Equipment</b>
                    <span style="float:right;">
  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseGameEquipment" aria-expanded="false" aria-controls="collapseGameEquipment" style="float:right;">
Show/Hide
  </button>
                    </span>
                </div>
                <div class="collapse" id="collapseGameEquipment">
                <div class="card-body">
                        <?php foreach ($gamePlayers as $gamePlayer): ?>
                            <?php $where = array(); ?>
                            <?php $where["playerId"] = $gamePlayer->userId; ?>
                            <?php $where["status"] = CampaignCharacter::STATUS_ACTIVE; ?>
                            <?php $characters = CampaignCharacter::find()->where($where)->all(); ?>
                                <?php foreach ($characters as $character): ?>
                                <?php if ($gamePlayer->characterId == $character->id): ?>
                                    <?php $chId = $character->id; ?>
                                    <?php $equips = Equipment::find()->where(["characterId" => $chId])->all(); ?>
                                    <?php if (empty($equips)): ?>
                                        <p>No Equipment found for <b><?= $character->name; ?></b></p>
                                    <?php endif; ?>
                                    <?php foreach($equips as $equip): ?>
                                        <?php if (empty($state[$equip->state+1])): ?>
                                            <?php continue; ?>
                                        <?php endif; ?>
                                        <div class="alert alert-warning">
                                            <h6><i class="fa fa-shield"></i>&nbsp;<?= $equip->name ?? ""; ?></h6>
                                            <p><?= $equip->description; ?></p>
                                            <p><b>Goal:</b> <?= $state[$equip->state+1] ?? "None"; ?></p>
                                            <?php $where = []; ?>
                                            <?php $where["equipmentId"] = $equip->id; ?>
                                            <?php if (!empty($state[$equip->state+1])): ?>
                                                <?php $where["name"] = $state[$equip->state+1]; ?>
                                            <?php else: ?>
                                                <?php $where["name"] = $state[$equip->state]; ?>
                                            <?php endif; ?>
                                            <?php $goals = EquipmentGoal::find()->where($where)->all(); ?>
                                            <?php foreach ($goals as $goal): ?>
                                                <p><?= $goal->description; ?></p>
                                                <?php $wh = []; ?>
                                                <?php $wh["equipmentGoalId"] = $goal->id; ?> 
                                                <?php $reqs = Egr::find()->where($wh)->all(); ?>
                                                <?php foreach ($reqs as $req): ?>
                                                    <p>
                                                        <i class="fa fa-flag"></i>&nbsp;<?= $req->name; ?> / 
                                                        <?= $req->progress; ?>%
                                                    </p>
                                                <?php endforeach; ?>
                                            <?php endforeach; ?>
                                            <i class="fa fa-user"></i>&nbsp;<?= $character->name; ?>
                                        </div>
                                    <?php endforeach; ?> 
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                </div>
                <div class="card-footer">
                    <small style="color:#888">
                        <i class="fa fa-shield"></i>&nbsp;Equipment Name&nbsp;&nbsp;&nbsp;&nbsp;
                        <i class="fa fa-flag"></i>&nbsp;Goal Requirement&nbsp;&nbsp;&nbsp;&nbsp;
                        <i class="fa fa-user"></i>&nbsp;Character Name&nbsp;&nbsp;&nbsp;&nbsp;
                    </small>
                    <br />
                </div>
                </div>
            </div>
    <?php endif; ?>
<?php endif; ?>
