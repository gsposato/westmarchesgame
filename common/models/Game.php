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
            [['campaignId', 'goldPayoutPerPlayer', 'baseBastionPointsPerPlayer', 'bonusBastionPointsPerPlayer', 'credit', 'owner', 'creator', 'created', 'updated'], 'integer'],
            [['gameInviteLink', 'voiceVenueLink'], 'string'],
            [['name', 'levelRange', 'timeDuration'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'levelRange' => 'Level Range',
            'gameInviteLink' => 'Game Invite Link',
            'timeDuration' => 'Time Duration',
            'voiceVenueLink' => 'Voice Venue Link',
            'goldPayoutPerPlayer' => 'Gold Payout Per Player',
            'baseBastionPointsPerPlayer' => 'Base Bastion Points Per Player',
            'bonusBastionPointsPerPlayer' => 'Bonus Bastion Points Per Player',
            'credit' => 'Credit',
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
            return 0;
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
        $gamesBefore = Game::find()
            ->where(["campaignId" => $campaignId])
            ->andWhere(["<", "id", $gameId])
            ->all();
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
        return count($gamesBefore) + $defaultStartingGame + 1;
    }

    /**
     * Event
     */
    public static function event($gameId)
    {
        $gameEvent = GameEvent::find()->where(["gameId" => $gameId])->one();
        if (!$gameEvent) {
            return '';
        }
        $slot = GamePollSlot::findOne($gameEvent->gamePollSlotId);
        if (!$slot) {
            return '';
        }
        return date("M j, Y h:i:s A ", $slot->unixtime);
    }
}
