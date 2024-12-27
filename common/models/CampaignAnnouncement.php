<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "campaign_announcement".
 *
 * @property int $id
 * @property int $campaignId
 * @property string $name
 * @property string $note
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class CampaignAnnouncement extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'campaign_announcement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'name', 'note', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'owner', 'creator', 'created', 'updated'], 'integer'],
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
            'name' => 'Name',
            'note' => 'Note',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
