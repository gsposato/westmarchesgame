<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "campaign_character".
 *
 * @property int $id
 * @property int $campaignId
 * @property int $playerId
 * @property string $name
 * @property int $type
 * @property int $status
 * @property string|null $description
 * @property string|null $bastionName
 * @property string|null $bastionType
 * @property int $startingGold
 * @property int $startingBastionPoints
 * @property int $startingCredit
 * @property int $firstGamePlayed
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class CampaignCharacter extends NotarizedModel
{
    public const STATUS_NEW = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_RETIRED = 3;
    public const STATUS_DEAD = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'campaign_character';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'playerId', 'name', 'type', 'status', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'type', 'status', 'startingGold', 'startingBastionPoints', 'startingCredit', 'owner', 'creator', 'created', 'updated'], 'integer'],
            [['description', 'bastionName', 'bastionType', 'firstGamePlayed'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'campaignId' => 'Campaign ID',
            'playerId' => 'Player',
            'name' => 'Name',
            'type' => 'Type',
            'status' => 'Status',
            'description' => 'Description',
            'bastionName' => 'Bastion Name',
            'bastionType' => 'Bastion Type',
            'startingGold' => 'Starting Gold',
            'startingBastionPoints' => 'Starting Bastion Points',
            'startingCredit' => 'Starting Credit',
            'firstGamePlayed' => 'First Game Played',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * Before Save
     */
    public function beforeSave($insert)
    {
        $this->firstGamePlayed = strval(strtotime($this->firstGamePlayed));
        return parent::beforeSave($insert);
    }

    /**
     * Get Type
     */
    public static function type()
    {
        return [
            1 => "Player Character (PC)",
            2 => "Non Playable Character (NPC)"
        ];
    }

    /**
     * Get Status
     */
    public static function status()
    {
        return [
            1 => "New",
            2 => "Active",
            3 => "Retired",
            4 => "Dead"
        ];
    }

    /**
     * Character Levels
     * @param integer $campaignId
     */
    public static function levels($campaignId)
    {
        $characters = self::find()
            ->where(["campaignId" => $campaignId])
            ->andWhere(["status" => self::STATUS_ACTIVE])
            ->all();
        if (empty($characters)) {
            return;
        }
        $levels = array();
        foreach ($characters as $character) {
            $gamesPlayed = GamePlayer::find()
                ->where(["characterId" => $character->id])
                ->all();
            $advancement = self::advancement($campaignId, $gamesPlayed);
            if (empty($levels[$advancement])) {
                $levels[$advancement] = 0;
            }
            $levels[$advancement]++;
        }
        return $levels;
    }

    /**
     * Character Advancement
     * @param integer $campaignId
     * @param integer $gamesPlayed
     * @param integer $startingCredit
     */
    public static function advancement($campaignId, $gamesPlayed, $startingCredit = 0)
    {
        $campaign = Campaign::findOne($campaignId);
        if (!$campaign) {
            return 0;
        }
        $rules = json_decode($campaign->rules);
        if (empty($rules)) {
            return 0;
        }
        if (empty($rules->CampaignCharacter->startingLevel)) {
            return 0;
        }
        if (empty($rules->CampaignCharacter->GameLevelAdvancement)) {
            return 0;
        }
        $credit = $startingCredit ?? 0;
        foreach ($gamesPlayed as $gamePlayed) {
            $game = Game::findOne($gamePlayed->gameId);
            if (empty($game)) {
                continue;
            }
            if (!$game->isEnded()) {
                continue;
            }
            if (!$game) {
                $credit += 1;
                continue;
            }
            $credit += $game->credit;
        }
        $currentAdvancement = $rules->CampaignCharacter->startingLevel;
        foreach ($rules->CampaignCharacter->GameLevelAdvancement as $games => $advancement) {
            if ($credit < $games) {
                return $currentAdvancement;
            }
            $currentAdvancement = $advancement;
        }
        return $currentAdvancement;
    }

    /**
     * Get Previous Game Date
     * @param integer $characterId
     */
    public static function previous($characterId)
    {
        $character = CampaignCharacter::findOne($characterId);
        $firstGamePlayed = date("m/d/Y", $character->firstGamePlayed);
        $lastGamePlayed = GamePlayer::find()
            ->where(["characterId" => $characterId])
            ->andWhere(['or',
                    ['status' => GamePlayer::STATUS_SCHEDULED],
                    ['status' => GamePlayer::STATUS_ACTIVATED]
            ])
            ->orderBy(["id" => SORT_DESC])
            ->one();
        if (empty($lastGamePlayed)) {
            return $firstGamePlayed;
        }
        $gameEvent = GameEvent::find()
            ->where(["gameId" => $lastGamePlayed->gameId])
            ->andWhere(["!=", "owner", $lastGamePlayed->userId])
            ->one();
        if (empty($gameEvent)) {
            return $firstGamePlayed;
        }
        $now = time();
        $gamePollSlot = GamePollSlot::findOne($gameEvent->gamePollSlotId);
        if (empty($gamePollSlot->unixtime)) {
            return $firstGamePlayed;
        }
        $campaignPlayer = CampaignPlayer::findOne($lastGamePlayed->userId);
        if (empty($campaignPlayer)) {
            return date("m/d/Y", $gamePollSlot->unixtime);
        }
        if ($campaignPlayer->gameEventTimestamp < $gamePollSlot->unixtime) {
            $campaignPlayer->gameEventTimestamp = $gamePollSlot->unixtime;
            $campaignPlayer->save();
        }
        return date("m/d/Y", $gamePollSlot->unixtime);
    }

    /**
     * Is Host Character
     * @param integer $gameId
     * @param integer $characterId
     */
    public static function isHostCharacter($gameId, $characterId)
    {
        $character = self::findOne($characterId);
        if (empty($character->playerId)) {
            return false;
        }
        $player = CampaignPlayer::findOne($character->playerId);
        if (empty($player->userId)) {
            return false;
        }
        $user = User::findOne($player->userId);
        if (empty($user->id)) {
            return false;
        }
        $game = Game::findOne($gameId);
        if (empty($game->owner)) {
            return false;
        }
        if ($user->id == $game->owner) {
            return true;
        }
        $gamePlayer = GamePlayer::find()
            ->where(["gameId" => $gameId])
            ->andWhere(["characterId" => $characterId])
            ->andWhere(["status" => GamePlayer::STATUS_COHOST])
            ->one();
        if (!empty($gamePlayer)) {
            return true;
        }
        return false;
    }
}
