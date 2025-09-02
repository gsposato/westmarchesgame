<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "game".
 *
 * @property int $id
 * @property int $campaignId
 * @property string $name
 * @property string|null $levelRange
 * @property string|null $gameInviteLink
 * @property string|null $timeDuration
 * @property string|null $voiceVenueLink
 * @property int|null $goldPayoutPerPlayer
 * @property int|null $baseBastionPointsPerPlayer
 * @property int|null $bonusBastionPointsPerPlayer
 * @property int $credit
 * @property int $host
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class Game extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'name', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'goldPayoutPerPlayer', 'baseBastionPointsPerPlayer', 'bonusBastionPointsPerPlayer', 'credit', 'host', 'owner', 'creator', 'created', 'updated', 'category'], 'integer'],
            [['gameInviteLink', 'voiceVenueLink'], 'string'],
            [['name', 'levelRange', 'timeDuration'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'levelRange' => 'Level Range',
            'gameInviteLink' => 'Game Invite Link',
            'timeDuration' => 'Time Duration',
            'voiceVenueLink' => 'Voice Venue Link',
            'goldPayoutPerPlayer' => /* put gold var here */ $gold . ' Payout Per Player',
            'baseBastionPointsPerPlayer' => $bastion . ' Per Player',
            'bonusBastionPointsPerPlayer' => $bastion . ' Per Player',
            'credit' => $credit,
            'host' => 'Host',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * Get Duration As Seconds
    */
    public function durationInSeconds()
    {
        preg_match_all('/\d+/', $this->timeDuration, $matches);
        $numbers = array_map('intval', $matches[0]);
        $maxNumber = max($numbers);
        return $maxNumber * 60 * 60;
    }

    /**
     * Is Ended
     */
    public function isEnded()
    {
        $gameEvent = GameEvent::find()->where(["gameId" => $this->id])->one();
        if (empty($gameEvent)) {
            return false;
        }
        $slot = GamePollSlot::findOne($gameEvent->gamePollSlotId);
        if (empty($slot)) {
            return false;
        }
        $now = time();
        $timestamp = $slot->unixtime;
        $duration = $this->durationInSeconds();
        $end = $timestamp + $duration;
        if ($now > $end) {
            return true;
        }
        return false;
    }

    /**
     * Game Session
     * @param integer $gameId
     */
    public static function session($gameId)
    {
        $unknown = '<i class="fa fa-question-circle" aria-hidden="true"></i>';
        if (empty($_GET['campaignId'])) {
            return 0;
        }
        $campaignId = $_GET['campaignId'];
        $campaign = Campaign::findOne($campaignId);
        if (empty($campaign->rules)) {
            return $unknown;
        }
        $game = Game::findOne($gameId);
        if (!$game) {
            return $unknown;
        }
        if ($game->category != 1) {
            $rules = json_decode($campaign->rules);
            $icon = $game->categories($rules, "icon", $game->category);
            $unknown = '<i class="fa '.$icon.'" aria-hidden="true"></i>';
            return $unknown;
        }
        $gameEvent = GameEvent::find()->where(["gameId" => $gameId])->one();
        if (!$gameEvent) {
            return $unknown;
        }
        $slot = GamePollSlot::findOne($gameEvent->gamePollSlotId);
        if (!$slot) {
            return $unknown;
        }
        $timestamp = $slot->unixtime;
        $campaignRules = json_decode($campaign->rules);
        $defaultStartingGame = $campaignRules->Game->startingGame ?? 0;
        $gamesBeforeSql = <<<SQL
SELECT ge.*, gps.unixtime
FROM game_event ge
JOIN game_poll_slot gps ON ge.gamePollSlotId = gps.id
WHERE gps.unixtime < :timestamp
ORDER BY gps.unixtime;
SQL;
        $gamesBefore = Yii::$app
            ->db
            ->createCommand($gamesBeforeSql)
            ->bindValue(":timestamp", $timestamp)
            ->queryAll();
        $gamesBeforeNumber = 0;
        foreach ($gamesBefore as $gameBefore) {
            if (empty($gameBefore["gameId"])) {
                continue;
            }
            $game = Game::findOne($gameBefore["gameId"]);
            if ($game->campaignId != $_GET["campaignId"]) {
                continue;
            }
            if (!empty($game->deleted)) {
                continue;
            }
            if ($game->category != 1) {
                continue;
            }
            $gamesBeforeNumber++;
        }
        return $gamesBeforeNumber + $defaultStartingGame + 1;
    }

    /**
     * Event
     * @param integer $gameId
     * @param string $format
     */
    public static function event($gameId, $format = "")
    {
        $gameEvent = GameEvent::find()->where(["gameId" => $gameId])->one();
        if (!$gameEvent) {
            return '';
        }
        $slot = GamePollSlot::findOne($gameEvent->gamePollSlotId);
        if (!$slot) {
            return '';
        }
        if (empty($format)) {
            return date("M j, Y h:i:s A ", $slot->unixtime);
        }
        return date($format, $slot->unixtime);
    }

    /**
     * Get Host
     */
    public function host()
    {
        if (!empty($this->host)) {
            return $this->host;
        }
        return $this->owner;
    }

    /**
     * Categories
     * @param object $rules
     * @param string $type
     * @param integer $value
     */
    public function categories($rules, $type, $value = 0)
    {
        if (empty($rules->GameCategory)) {
            if (!empty($value)) {
                return $value;
            }
            return;
        }
        $arr = [];
        $counter = 0;
        $categories = $rules->GameCategory;
        foreach ($categories as $name => $attributes) {
            $counter++;
            if ($type == "select") {
                $arr[$counter] = $name;
            }
            if ($type == "name" && !empty($value)) {
                if ($counter == $value) {
                    return $name;
                }
            }
            if ($counter == $value) {
                return $attributes->{$type};
            }
        }
        return $arr;
    }
}
