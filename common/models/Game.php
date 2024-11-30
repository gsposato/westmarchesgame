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
 * @property int $credit
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class Game extends \yii\db\ActiveRecord
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
            [['campaignId', 'goldPayoutPerPlayer', 'credit', 'owner', 'creator', 'created', 'updated'], 'integer'],
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
            'credit' => 'Credit',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
