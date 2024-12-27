<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "player_complaint".
 *
 * @property int $id
 * @property int $campaignId
 * @property int|null $gameId
 * @property int $reportingUserId
 * @property int $reportingCharacterId
 * @property int $offendingUserId
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
            [['campaignId', 'reportingUserId', 'reportingCharacterId', 'offendingUserId', 'offendingCharacterId', 'note', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'gameId', 'reportingUserId', 'reportingCharacterId', 'offendingUserId', 'offendingCharacterId', 'owner', 'creator', 'created', 'updated'], 'integer'],
            [['note'], 'string'],
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
            'reportingUserId' => 'Reporting User ID',
            'reportingCharacterId' => 'Reporting Character ID',
            'offendingUserId' => 'Offending User ID',
            'offendingCharacterId' => 'Offending Character ID',
            'note' => 'Note',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
