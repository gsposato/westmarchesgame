<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "campaign_player".
 *
 * @property int $id
 * @property int $campaignId
 * @property int $userId
 * @property int $isPlayer
 * @property int $isHost
 * @property int $isAdmin
 * @property int $created
 * @property int $updated
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
            [['campaignId', 'userId', 'created', 'updated'], 'required'],
            [['campaignId', 'userId', 'isPlayer', 'isHost', 'isAdmin', 'created', 'updated'], 'integer'],
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
            'userId' => 'User ID',
            'isPlayer' => 'Is Player',
            'isHost' => 'Is Host',
            'isAdmin' => 'Is Admin',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
