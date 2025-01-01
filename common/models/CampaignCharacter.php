<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "campaign_character".
 *
 * @property int $id
 * @property int $campaignId
 * @property int $playerId
 * @property string $name
 * @property int $type
 * @property int $status
 * @property string|null $description
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class CampaignCharacter extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'campaign_character';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'playerId', 'name', 'type', 'status', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'type', 'status', 'owner', 'creator', 'created', 'updated'], 'integer'],
            [['description'], 'string'],
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
            'playerId' => 'Player ID',
            'name' => 'Name',
            'type' => 'Type',
            'status' => 'Status',
            'description' => 'Description',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * Get Type
     */
    public static function type()
    {
        return [
            1 => "Player Character (PC)",
            2 => "Non Playable Character (NPC)"
        ];
    }

    /**
     * Get Status
     */
    public static function status()
    {
        return [
            1 => "New",
            2 => "Active",
            3 => "Retired",
            4 => "Dead"
        ];
    }
}
