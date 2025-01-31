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

    public const BONUS_NORMAL = 1;
    public const BONUS_BASTION = 2;
    public const BONUS_DOUBLE_GOLD = 3;
    public const BONUS_DOUBLE_GOLD_BASTION = 4;
    public const BONUS_NOTHING = 0;

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
        $campaignId = $_GET['campaignId'];
        $characters = CampaignCharacter::find()
            ->where(["campaignId" => $campaignId])
            ->andWhere(["status" => CampaignCharacter::STATUS_ACTIVE])
            ->all();
        foreach ($characters as $character) {
            CampaignCharacter::previous($character->id);
        }
        $sql = <<<SQL
SELECT
    gp.*,
    cp.campaignId,
    cp.name AS campaignName,
    cp.isPlayer,
    cp.isHost,
    cp.isAdmin,
    cp.gameEventTimestamp,
    cp.created AS campaignCreated,
    cp.updated AS campaignUpdated
FROM
    game_player gp
INNER JOIN
    campaign_player cp
ON
    gp.userId = cp.id
WHERE
    cp.isPlayer = 1
AND
    gp.gameId = :gameId
ORDER BY
    cp.gameEventTimestamp ASC
SQL;
        $results = Yii::$app
            ->db
            ->createCommand($sql)
            ->bindValue(":gameId", $gameId)
            ->queryAll();
        foreach ($results as $result) {
            $players[] = GamePlayer::findOne($result["id"]);
        }
        return $players;
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

    /**
     * Bonus
     * @param boolean $isHost
     */
    public function bonus($isHost)
    {
        switch ($this->hasBonusPoints) {
            case self::BONUS_NORMAL:
                $this->hasBonusPoints = self::BONUS_BASTION;
                $this->save();
                break;
            case self::BONUS_BASTION:
                if ($isHost) {
                    $this->hasBonusPoints = self::BONUS_DOUBLE_GOLD;
                    $this->save();
                    return;
                }
                $this->hasBonusPoints = self::BONUS_NOTHING;
                $this->save();
                break;
            case self::BONUS_DOUBLE_GOLD:
                if ($isHost) {
                    $this->hasBonusPoints = self::BONUS_DOUBLE_GOLD_BASTION;
                    $this->save();
                    return;
                }
                $this->hasBonusPoints = self::BONUS_NOTHING;
                $this->save();
                break;
            case self::BONUS_DOUBLE_GOLD_BASTION:
                $this->hasBonusPoints = self::BONUS_NOTHING;
                $this->save();
                break;
            case self::BONUS_NOTHING:
                $this->hasBonusPoints = self::BONUS_NORMAL;
                $this->save();
                break;
        }
    }

}
