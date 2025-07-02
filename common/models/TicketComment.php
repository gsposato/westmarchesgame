<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ticket_comment".
 *
 * @property int $id
 * @property int $campaignId
 * @property int $ticketId
 * @property string $note
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class TicketComment extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket_comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'ticketId', 'note', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'ticketId', 'owner', 'creator', 'created', 'updated'], 'integer'],
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
            'ticketId' => 'Ticket ID',
            'note' => 'Note',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
