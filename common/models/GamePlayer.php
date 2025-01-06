<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "game_player".
 *
 * @property int $id
 * @property int $gameId
 * @property int|null $userId
 * @property int $characterId
 * @property int $status
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class GamePlayer extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game_player';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gameId', 'characterId', 'status', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['gameId', 'userId', 'characterId', 'status', 'owner', 'creator', 'created', 'updated'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gameId' => 'Game ID',
            'userId' => 'Player',
            'characterId' => 'Character ID',
            'status' => 'Status',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * Name
     */
    public function name()
    {
        $cp = CampaignPlayer::findOne($this->userId);
        if (!$cp) {
            return "";
        }
        return $cp->name;
    }

    /**
     * Organize
     * @param Integer gameId
     */
    public static function organize($gameId)
    {
        $list = [];
        $players = [];
        $gamePlayers = GamePlayer::find()
            ->where(["gameId" => $gameId])
            ->all();
        foreach ($gamePlayers as $gamePlayer) {
            $campaignPlayer = CampaignPlayer::findOne($gamePlayer->userId);
            if (!$campaignPlayer) {
                continue;
            }
            if (!$campaignPlayer->isPlayer) {
                continue;
            }
            if ($campaignPlayer->isHost) {
                continue;
            }
            if ($campaignPlayer->isAdmin) {
                continue;
            }
            if (empty($campaignPlayer->gameEventTimestamp)) {
                $campaignPlayer->gameEventTimestamp = rand(1, 1000000);
                $campaignPlayer->save();
            }
            if (empty($players[$campaignPlayer->gameEventTimestamp])) {
                $players[$campaignPlayer->gameEventTimestamp] = $gamePlayer;
            }
            $list[] = $gamePlayer->id;
        }
        ksort($players);
        $admins = [];
        foreach ($gamePlayers as $gamePlayer) {
            $campaignPlayer = CampaignPlayer::findOne($gamePlayer->userId);
            if (!$campaignPlayer) {
                continue;
            }
            if (in_array($gamePlayer->id, $list)) {
                continue;
            }
            if (empty($campaignPlayer->gameEventTimestamp)) {
                $campaignPlayer->gameEventTimestamp = rand(1, 1000000);
                $campaignPlayer->save();
            }
            if (empty($admins[$campaignPlayer->gameEventTimestamp])) {
                $admins[$campaignPlayer->gameEventTimestamp] = $gamePlayer;
            }
            $admins[$campaignPlayer->gameEventTimestamp] = $gamePlayer;
        }
        ksort($admins);
        return array_merge($players, $admins);
    }

    /**
     * Status
     * @param Mixed $string
     */
    public static function status($string)
    {
        if (!is_string($string)) {
            $string = strval($string);
        }
        switch ($string) {
            case "scheduled": return "1";
            case "reserved": return "2";
            case "dropout": return "3";
            case "activated": return "4";
            case "co-host": return "5";
            case "1": return "scheduled";
            case "2": return "reserved";
            case "3": return "dropout";
            case "4": return "activated";
            case "5": return "co-host";
            default:
                throw new \Exception("Unknown Status [$string]\n");
        }
    }
}
