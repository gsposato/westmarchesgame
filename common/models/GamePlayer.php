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
 * @property int $hasBonusPoints
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class GamePlayer extends NotarizedModel
{
    public const STATUS_SCHEDULED = 1;
    public const STATUS_RESERVED = 2;
    public const STATUS_DROPOUT = 3;
    public const STATUS_ACTIVATED = 4;
    public const STATUS_COHOST = 5;

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
            [['gameId', 'userId', 'characterId', 'status', 'hasBonusPoints', 'owner', 'creator', 'created', 'updated'], 'integer'],
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
            'hasBonusPoints' => 'Has Bonus Points',
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
     * Status Color
     */
    public function statusColor()
    {
        switch ($this->status) {
            case self::STATUS_SCHEDULED: return "btn-success";
            case self::STATUS_RESERVED: return "btn-warning";
            case self::STATUS_DROPOUT: return "btn-secondary";
            case self::STATUS_ACTIVATED: return "btn-primary";
            case self::STATUS_COHOST: return "btn-danger";
            default:
                throw new \Exception("Unknown Status [$this->status]\n");
        }
    }

    /**
     * Status Icon
     */
    public function statusIcon()
    {
        switch ($this->status) {
            case self::STATUS_SCHEDULED: return "fa-check";
            case self::STATUS_RESERVED: return "fa-clock";
            case self::STATUS_DROPOUT: return "fa-minus";
            case self::STATUS_ACTIVATED: return "fa-chevron-up";
            case self::STATUS_COHOST: return "fa-fire-flame-curved";
            default:
                throw new \Exception("Unknown Status [$this->status]\n");
        }
    }

    /**
     * Status Change
     * @param Integer $current
     */
    public static function change($current)
    {
        switch ($current) {
            case self::STATUS_SCHEDULED: return self::STATUS_RESERVED;
            case self::STATUS_RESERVED: return self::STATUS_DROPOUT;
            case self::STATUS_DROPOUT: return self::STATUS_ACTIVATED;
            case self::STATUS_ACTIVATED: return self::STATUS_COHOST;
            case self::STATUS_COHOST: return self::STATUS_SCHEDULED;
            default:
                throw new \Exception("Unknown Status [$current]\n");
        }
    }
}
