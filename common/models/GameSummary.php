<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "game_summary".
 *
 * @property int $id
 * @property int $gameId
 * @property string $note
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class GameSummary extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game_summary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gameId', 'note', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['gameId', 'owner', 'creator', 'created', 'updated'], 'integer'],
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
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
