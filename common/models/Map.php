<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "map".
 *
 * @property int $id
 * @property int $campaignId
 * @property string $name
 * @property string $image
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class Map extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'name', 'image', 'minzoom', 'maxzoom', 'defaultzoom', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'minzoom', 'maxzoom', 'defaultzoom', 'owner', 'creator', 'created', 'updated'], 'integer'],
            [['image'], 'string'],
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
            'image' => 'Image',
            'minzoom' => 'Min Zoom',
            'maxzoom' => 'Max Zoom',
            'defaultzoom' => 'Default Zoom',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
