<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "campaign_document".
 *
 * @property int $id
 * @property int $campaignId
 * @property string $name
 * @property string $url
 * @property int|null $playerVisible
 * @property int|null $hostVisible
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class CampaignDocument extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'campaign_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'name', 'url', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'playerVisible', 'hostVisible', 'owner', 'creator', 'created', 'updated'], 'integer'],
            [['url'], 'string'],
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
            'url' => 'Url',
            'playerVisible' => 'Player Visible',
            'hostVisible' => 'Host Visible',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
