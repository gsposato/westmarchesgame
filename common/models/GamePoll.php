<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "game_poll".
 *
 * @property int $id
 * @property int $gameId
 * @property string $note
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class GamePoll extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game_poll';
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
