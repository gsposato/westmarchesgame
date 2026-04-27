<?php

namespace console\controllers;

use yii\console\Controller;
use yii\console\ExitCode;

use common\models\Campaign;
use common\models\CampaignCharacter;
use common\models\GamePlayer;

/**
 * General purpose console commands.
 */
class CommandController extends Controller
{
    /**
     * Default action
     *
     * Usage:
     * php yii command/index
     */
    public function actionIndex()
    {
        $this->stdout("Command controller is working.\n");

        return ExitCode::OK;
    }

    /**
     * Compare Game Advancement
     *
     * Usage:
     * php yii command/compare-game-advancement <integer>
     */
    public function actionCompareGameAdvancement($campaignIdOld, $campaignIdNew)
    {
        $this->stdout("Running compare game advancement...\n");

        $campaignOld = Campaign::findOne($campaignIdOld);
        if (empty($campaignOld)) {
            $this->stdout("Could not find Campaign with id [$campaignIdOld].  Quitting.\n");
            return ExitCode::NOINPUT;
        }
        $campaignNew = Campaign::findOne($campaignIdNew);
        if (empty($campaignNew)) {
            $this->stdout("Could not find Campaign with id [$campaignIdNew].  Quitting.\n");
            return ExitCode::NOINPUT;
        }
        $characters = CampaignCharacter::find()->where(["campaignId" => $campaignIdOld])->all();
        if (empty($characters)) {
            $this->stdout("Could not find Characters in campaign [$campaignOld->name].  Quitting.\n");
        }
        $this->stdout("{\n");
        foreach ($characters as $character) {
            $gamesPlayed = GamePlayer::find()->where(["characterId" => $character->id])->all();
            $oldLevel = CampaignCharacter::advancement($campaignIdOld, $gamesPlayed, $character);
            $newLevel = CampaignCharacter::advancement($campaignIdNew, $gamesPlayed, $character);
            $this->stdout("\"" . $character->name . "\": [\"$oldLevel\", \"$newLevel\"],\n");
        }
        $this->stdout("}\n");
        return ExitCode::OK;
    }
}
