<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "campaign_player".
 *
 * @property int $id
 * @property int $campaignId
 * @property string|null $name
 * @property int|null $userId
 * @property int $isPlayer
 * @property int $isHost
 * @property int $isSupport
 * @property int $isSubscribed
 * @property int $isAdmin
 * @property int $created
 * @property int $updated
 * @property int $gameEventTimestamp
 * @property int $gameEventNumber
 */
class CampaignPlayer extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'campaign_player';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'created', 'updated'], 'required'],
            [['campaignId', 'userId', 'isPlayer', 'isHost', 'isAdmin', 'isSupport', 'isSubscribed', 'created', 'updated'], 'integer'],
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
            'name' => 'Name',
            'userId' => 'User ID',
            'isPlayer' => 'Is Player',
            'isHost' => 'Is Host',
            'isSupport' => 'Is Support',
            'isSubscribed' => 'Is Subscribed',
            'isAdmin' => 'Is Admin',
            'created' => 'Created',
            'updated' => 'Updated',
            'gameEventTimestamp' => 'Game Event Timestamp',
            'gameEventNumber' => 'Previous Games Hosted',
        ];
    }

    /**
     * Games Played excludes games hosted
     */
    public function played()
    {
        $number = 0;
        $characters = CampaignCharacter::find()
            ->where(["playerId" => $this->id])
            ->all();
        foreach ($characters as $character) {
            $number += $character->startingCredit;
        }
        $number -= $this->gameEventNumber;
        $gamesPlayed = GamePlayer::find()
            ->where(["userId" => $this->id])
            ->all();
        foreach ($gamesPlayed as $gamePlayed) {
            switch ($gamePlayed->status) {
                case GamePlayer::STATUS_RESERVED:
                case GamePlayer::STATUS_DROPOUT:
                case GamePlayer::STATUS_COHOST:
                    continue 2;
                case GamePlayer::STATUS_SCHEDULED:
                case GamePlayer::STATUS_ACTIVATED:
                    break;
            }
            $gameEvent = GameEvent::find()
                ->where(["gameId" => $gamePlayed->gameId])
                ->andWhere(["!=", "owner", $this->id])
                ->one();
            if (!empty($gameEvent)) {
                $number++;
            }
        }
        return $number;
    }
}
