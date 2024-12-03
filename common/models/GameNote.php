<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "game_note".
 *
 * @property int $id
 * @property int $gameId
 * @property string $note
 * @property int $pinned
 * @property int $inGamePoll
 * @property int $inGameEvent
 * @property int $inGameSummary
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class GameNote extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game_note';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gameId', 'note', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['gameId', 'pinned', 'inGamePoll', 'inGameEvent', 'inGameSummary', 'owner', 'creator', 'created', 'updated'], 'integer'],
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
            'note' => 'Note',
            'pinned' => 'Pinned',
            'inGamePoll' => 'In Game Poll',
            'inGameEvent' => 'In Game Event',
            'inGameSummary' => 'In Game Summary',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
