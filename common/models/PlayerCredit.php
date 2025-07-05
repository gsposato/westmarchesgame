<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "player_credit".
 *
 * @property int $id
 * @property int $campaignId
 * @property int $userId
 * @property int|null $gameId
 * @property int|null $characterId
 * @property int $category
 * @property float $amount
 * @property string|null $note
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class PlayerCredit extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'player_credit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'userId', 'category', 'amount', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'userId', 'gameId', 'characterId', 'category', 'owner', 'creator', 'created', 'updated'], 'integer'],
            [['amount'], 'number'],
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
            'userId' => 'User ID',
            'gameId' => 'Game ID',
            'characterId' => 'Character ID',
            'category' => 'Category',
            'amount' => 'Amount',
            'note' => 'Note',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * Delete
     * @desc override default delete behavior
     */
    public function delete()
    {
        return;
    }
}
