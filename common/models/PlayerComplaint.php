<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "player_complaint".
 *
 * @property int $id
 * @property int $campaignId
 * @property int|null $gameId
 * @property string $name
 * @property int $reportingPlayerId
 * @property int $reportingCharacterId
 * @property int $offendingPlayerId
 * @property int $offendingCharacterId
 * @property string $note
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class PlayerComplaint extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'player_complaint';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'name', 'reportingPlayerId', 'reportingCharacterId', 'offendingPlayerId', 'offendingCharacterId', 'note', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'gameId', 'reportingPlayerId', 'reportingCharacterId', 'offendingPlayerId', 'offendingCharacterId', 'owner', 'creator', 'created', 'updated'], 'integer'],
            [['note'], 'string'],
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
            'gameId' => 'Game ID',
            'name' => 'Name',
            'reportingPlayerId' => 'Reporting Player',
            'reportingCharacterId' => 'Reporting Character',
            'offendingPlayerId' => 'Offending Player',
            'offendingCharacterId' => 'Offending Character',
            'note' => 'Note',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
