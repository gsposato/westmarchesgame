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

    public const ROLE_HOST = "HC";
    public const ROLE_PLAYER = "PC";

    public const GAME_BONUS = [
        "Normal Bonus" => [
            "alert" => "success",
            "icon" => "fa-check",
            "roles" => [
                "PC" => 1,
                "HC" => 1
            ],
            "rewards" => [
                "credit" => 1,
                "bastion points" => 1,
                "bonus bastion points" => 0,
                "gold" => 1
            ]
        ],
        "Bastion Bonus" => [
            "alert" => "primary",
            "icon" => "fa-house",
            "roles" => [
                "PC" => 1,
                "HC" => 1
            ],
            "rewards" => [
                "credit" => 1,
                "bastion points" => 1,
                "bonus bastion points" => 1,
                "gold" => 1
            ]
        ],
        "Double Gold Bonus" => [
            "alert" => "warning",
            "icon" => "fa-coins",
            "roles" => [
                "PC" => 0,
                "HC" => 1
            ],
            "rewards" => [
                "credit" => 0,
                "bastion points" => 1,
                "bonus bastion points" => 0,
                "gold" => 2
            ]
        ],
        "Double Gold Bastion Bonus" => [
            "alert" => "danger",
            "icon" => "fa-flag",
            "roles" => [
                "PC" => 0,
                "HC" => 1
            ],
            "rewards" => [
                "credit" => 0,
                "bastion points" => 1,
                "bonus bastion points" => 1,
                "gold" => 2
            ]
        ],
        "Nothing" => [
            "alert" => "secondary",
            "icon" => "fa-minus",
            "roles" => [
                "PC" => 1,
                "HC" => 1
            ],
            "rewards" => [
                "credit" => 0,
                "bastion points" => 0,
                "bonus bastion points" => 0,
                "gold" => 0
            ]
        ]
    ];


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
AND
    cp.campaignId = :campaignId
ORDER BY
    cp.gameEventTimestamp ASC
SQL;
        $results = Yii::$app
            ->db
            ->createCommand($sql)
            ->bindValue(":gameId", $gameId)
            ->bindValue(":campaignId", $campaignId)
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
     * @param boolean $view
     */
    public function bonus($isHost, $view = false)
    {
        $emptyButton = [
            "name" => "Nothing",
            "alert" => "secondary",
            "icon" => "fa-minus"
        ];
        $counter = 0;
        $gameBonus = self::bonuses();
        $hasBonusPointsOriginal = $this->hasBonusPoints;
        foreach ($gameBonus as $gameBonusName => $gameBonusDetails) {
            $counter++;
            $button = [
                "name" => $gameBonusName,
                "alert" => $gameBonusDetails->alert,
                "icon" => $gameBonusDetails->icon
            ];
            if ($counter == 1) {
                $firstbutton = $button;
            }
            $bonusAvailable = $this->bonusAvailable($isHost, $gameBonusDetails->roles);
            if ($this->hasBonusPoints <= $counter && $bonusAvailable && $view) {
                break;
            }
            if ($this->hasBonusPoints < $counter && $bonusAvailable) {
                break;
            }
        }
        $gameBonusArr = (array) $gameBonus;
        if ($view) {
            if (empty($hasBonusPointsOriginal)) {
                return $emptyButton;
            }
            return $button;
        }
        $this->hasBonusPoints = $counter;
        if ($hasBonusPointsOriginal >= count($gameBonusArr)) {
            $this->hasBonusPoints = 1;
        }
        $this->save();
    }

    /**
     * Bonuses
     */
    public static function bonuses()
    {
        $campaign = Campaign::findOne($_GET['campaignId'] ?? 0);
        if (!$campaign) {
            return;
        }
        $rules = json_decode($campaign->rules);
        if (empty($rules)) {
            return;
        }
        if (empty($rules->GameBonus)) {
            return;
        }
        return $rules->GameBonus;
    }

    /**
     * Bonus Available
     * @param boolean $isHost
     * @param object $roles
     */
    protected function bonusAvailable($isHost, $roles)
    {
        if (empty($roles)) {
            return false;
        }
        foreach ($roles as $key => $value) {
            if ($isHost) {
                if ($key != self::ROLE_HOST) {
                    continue;
                }
                if (!$value) {
                    return false;
                }
            }
            if ($key != self::ROLE_PLAYER) {
                continue;
            }
            if (!$value) {
                return false;
            }
        }
        return true;
    }
}
