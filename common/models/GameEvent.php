<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "game_event".
 *
 * @property int $id
 * @property int $gameId
 * @property int $gamePollSlotId
 * @property string $note
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class GameEvent extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gameId', 'gamePollSlotId', 'note', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['gameId', 'gamePollSlotId', 'owner', 'creator', 'created', 'updated'], 'integer'],
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
            'gameId' => 'Game ID',
            'gamePollSlotId' => 'Game Poll Slot ID',
            'note' => 'Note',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
