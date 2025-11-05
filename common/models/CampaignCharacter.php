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
 * @property string|null $extra
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
    public const STATUS_HIBERNATE = 5;

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
            [['description', 'bastionName', 'bastionType', 'firstGamePlayed', 'extra'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $campaign = Campaign::findOne($_GET['campaignId']);
        $campaignRules = json_decode($campaign->rules);
        $gold = ucwords($campaignRules->Currency->gold ?? "gold");
        $bastion = ucwords($campaignRules->Currency->{"bastion points"} ?? "bastion points");
        $credit = ucwords($campaignRules->Currency->credit ?? "credit");
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
            'startingGold' => 'Starting ' . $gold,
            'startingBastionPoints' => 'Starting ' . $bastion,
            'startingCredit' => 'Starting ' . $credit,
            'firstGamePlayed' => 'First Game Played',
            'extra' => 'Extra',
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
    public static function status($get = 0)
    {
        $status = [
            1 => "New",
            2 => "Active",
            3 => "Retired",
            4 => "Dead",
            5 => "Hibernate"
        ];
        if (empty($get)) {
            return $status;
        }
        $get = floor($get);
        if ($get > 5) {
            $get = 5;
        }
        if ($get < 1) {
            $get = 1;
        }
        return $status[$get];
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
            $advancement = self::advancement($campaignId, $gamesPlayed, $character);
            if (empty($levels[$advancement])) {
                $levels[$advancement] = 0;
            }
            $levels[$advancement]++;
        }
        ksort($levels);
        return $levels;
    }

    /**
     * Character Advancement
     * @param integer $campaignId
     * @param object $gamesPlayed
     * @param object $character
     */
    public static function advancement($campaignId, $gamesPlayed, $character)
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
        $credit = $character->startingCredit ?? 0;
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
            if (empty($character->id)) {
                $credit += $game->credit;
                continue;
            }
            $gamePlayer = GamePlayer::find()
                ->where(["gameId" => $gamePlayed->gameId])
                ->andWhere(["characterId" => $character->id])
                ->one();
            if (empty($gamePlayer)) {
                continue;
            }
            if (empty($gamePlayer->hasBonusPoints)) {
                continue;
            }
            $multiplier = self::multiplier($rules, "credit", $gamePlayer->hasBonusPoints);
            $credit += ($game->credit * $multiplier);
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
     * Multiplier
     * @param object $rules
     * @param string $key
     * @param integer $value
     */
    public static function multiplier($rules, $key, $value)
    {
        $bonuses = $rules->GameBonus ?? GamePlayer::GAME_BONUS;
        $counter = 0;
        foreach ($bonuses as $bonus) {
            $counter++;
            if ($counter != $value) {
                continue;
            }
            foreach ($bonus as $bonusAttribute => $bonusValue) {
                if ($bonusAttribute != "rewards") {
                    continue;
                }
                if (empty($bonusValue)) {
                    continue;
                }
                foreach ($bonusValue as $rewardName => $rewardValue) {
                    if (empty($rewardValue)) {
                        continue;
                    }
                    if ($key == $rewardName) {
                        return $rewardValue;
                    }
                }
            }
        }
        return 0;
    }

    /**
     * Get Previous Game Date
     * @param integer $characterId
     */
    public static function previous($characterId)
    {
        $character = CampaignCharacter::findOne($characterId);
        if (empty($character)) {
            return;
        }
        $campaignPlayer = CampaignPlayer::findOne($character->playerId) ?? new CampaignPlayer();
        $firstGamePlayedTimestamp = $character->firstGamePlayed;
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
            if ($campaignPlayer->gameEventTimestamp < $firstGamePlayedTimestamp) {
                $campaignPlayer->gameEventTimestamp = $firstGamePlayedTimestamp;
                $campaignPlayer->save();
            }
            return $firstGamePlayed;
        }
        $gameEvent = GameEvent::find()
            ->where(["gameId" => $lastGamePlayed->gameId])
            ->andWhere(["!=", "owner", $lastGamePlayed->userId])
            ->one();
        if (empty($gameEvent)) {
            if ($campaignPlayer->gameEventTimestamp < $firstGamePlayedTimestamp) {
                $campaignPlayer->gameEventTimestamp = $firstGamePlayedTimestamp;
                $campaignPlayer->save();
            }
            return $firstGamePlayed;
        }
        $gamePollSlot = GamePollSlot::findOne($gameEvent->gamePollSlotId);
        if (empty($gamePollSlot->unixtime)) {
            if ($campaignPlayer->gameEventTimestamp < $firstGamePlayedTimestamp) {
                $campaignPlayer->gameEventTimestamp = $firstGamePlayedTimestamp;
                $campaignPlayer->save();
            }
            return $firstGamePlayed;
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
        $gamePlayer = GamePlayer::find()
            ->where(["gameId" => $gameId])
            ->andWhere(["characterId" => $characterId])
            ->andWhere(["status" => GamePlayer::STATUS_COHOST])
            ->one();
        if (!empty($gamePlayer)) {
            return true;
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
        if (empty($game->host())) {
            return false;
        }
        if ($user->id == $game->host()) {
            return true;
        }
        return false;
    }

    /**
     * Get Remaining Bastion Points
     */
    public function getRemainingBastionPoints()
    {
        $bp = "bastion points";
        $bbp = "bonus bastion points";
        $campaign = Campaign::findOne($_GET['campaignId']);
        $rules = json_decode($campaign->rules);
        $defaultStartingBastionPoints = $rules->CampaignCharacter->startingBastionPoints ?? 25;
        $totalBastionPointsEarned = $this->startingBastionPoints ?? $defaultStartingBastionPoints;
        $bastionPointsEarned = $totalBastionPointsEarned;
        $gamesPlayed = GamePlayer::find()->where(["characterId" => $this->id])->all();
        foreach ($gamesPlayed as $gamePlayed) {
            $game = Game::findOne($gamePlayed->gameId);
            if (empty($game)) {
                continue;
            }
            if (!$game->isEnded()) {
                continue;
            }
            $hbp = $gamePlayed->hasBonusPoints;
            $bastionPointsEarned += $game->baseBastionPointsPerPlayer * self::multiplier($rules, $bp, $hbp);
            $bastionPointsEarned += $game->bonusBastionPointsPerPlayer * self::multiplier($rules, $bbp, $hbp);
        }
        $bastionPointsSpent = 0;
        $purchases = Purchase::find()->where(["characterId" => $this->id])->all();
        foreach ($purchases as $purchase) {
            if ($purchase->currency != 2) {
                continue;
            }
            if (empty($purchase->gameId)) {
                $bastionPointsSpent += $purchase->price;
            }
        }
        return $bastionPointsEarned - $bastionPointsSpent;
    }

    /**
     * Get Total Game Credit
     */
    public function getTotalGameCredit()
    {
        $isCreditWorthy = [];
        $isBastionWorthy = [];
        $isBonusBastionWorthy = [];
        $isGoldWorthy = [];
        $isDoubleGoldWorthy = [];
        $bonuses = GamePlayer::bonuses();
        $counter = 0;
        if (empty($bonuses)) {
            return 0;
        }
        foreach ($bonuses as $name => $bonus) {
            $counter++;
            foreach ($bonus as $bonusAttribute => $bonusValue) {
                if ($bonusAttribute != "rewards") {
                    continue;
                }
                if (empty($bonusValue)) {
                    continue;
                }
                foreach ($bonusValue as $rewardName => $rewardValue) {
                    if (empty($rewardValue)) {
                        continue;
                    }
                    switch ($rewardName) {
                        case "credit": $isCreditWorthy[$counter] = $rewardValue; break;
                        case "bastion points": $isBastionWorthy[$counter] = $rewardValue; break;
                        case "bonus bastion points": $isBonusBastionWorthy[$counter] = $rewardValue; break;
                        case "gold": $isGoldWorthy[$counter] = $rewardValue; break;
                        default: // do nothing
                    }
                }
            }
        }
        $gamesPlayed = GamePlayer::find()
            ->where(["characterId" => $this->id])
            ->all();
        $totalCreditsEarned = 0;
        foreach ($gamesPlayed as $gamePlayed) {
            $game = Game::findOne($gamePlayed->gameId);
            if (empty($game)) {
                continue;
            }
            if (!$game->isEnded()) {
                continue;
            }
            if (array_key_exists($gamePlayed->hasBonusPoints, $isCreditWorthy)) {
                $multiplier = $isCreditWorthy[$gamePlayed->hasBonusPoints];
                $credit = ($game->credit * $multiplier);
                $totalCreditsEarned += $credit;
            }
        }
        return $totalCreditsEarned + ($this->startingCredit ?? 0);
    }

}
