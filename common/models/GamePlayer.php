<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "game_player".
 *
 * @property int $id
 * @property int $gameId
 * @property int|null $userId
 * @property int $characterId
 * @property int $status
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class GamePlayer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game_player';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gameId', 'characterId', 'status', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['gameId', 'userId', 'characterId', 'status', 'owner', 'creator', 'created', 'updated'], 'integer'],
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
            'userId' => 'User ID',
            'characterId' => 'Character ID',
            'status' => 'Status',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
