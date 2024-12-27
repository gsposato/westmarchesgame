<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "campaign_character".
 *
 * @property int $id
 * @property int $campaignId
 * @property string $name
 * @property int $slot
 * @property int $status
 * @property string|null $kanka
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class CampaignCharacter extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'campaign_character';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'name', 'slot', 'status', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'slot', 'status', 'owner', 'creator', 'created', 'updated'], 'integer'],
            [['kanka'], 'string'],
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
            'slot' => 'Slot',
            'status' => 'Status',
            'kanka' => 'Kanka',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
