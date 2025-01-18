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
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class CampaignCharacter extends NotarizedModel
{
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
            [['campaignId', 'type', 'status', 'owner', 'creator', 'created', 'updated'], 'integer'],
            [['description'], 'string'],
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
            'playerId' => 'Player ID',
            'name' => 'Name',
            'type' => 'Type',
            'status' => 'Status',
            'description' => 'Description',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
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
        $characters = CampaignCharacter::find()
            ->where(["campaignId" => $campaignId])
            ->andWhere(["status" => 2])
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
     */
    public static function advancement($campaignId, $gamesPlayed)
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
        $credit = 0;
        foreach ($gamesPlayed as $gamePlayed) {
            $game = Game::findOne($gamePlayed->gameId);
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
}
