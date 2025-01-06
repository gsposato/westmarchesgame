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
            [['campaignId', 'userId', 'isPlayer', 'isHost', 'isAdmin', 'created', 'updated'], 'integer'],
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
            'isAdmin' => 'Is Admin',
            'created' => 'Created',
            'updated' => 'Updated',
            'gameEventTimestamp' => 'Game Event Timestamp',
            'gameEventNumber' => 'Game Event Number',
        ];
    }
}
