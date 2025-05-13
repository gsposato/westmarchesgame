<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "map_marker".
 *
 * @property int $id
 * @property int $campaignId
 * @property int $mapId
 * @property string $name
 * @property string $color
 * @property float $lat
 * @property float $lng
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class MapMarker extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'map_marker';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'mapId', 'name', 'color', 'lat', 'lng', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'mapId', 'owner', 'creator', 'created', 'updated'], 'integer'],
            [['lat', 'lng'], 'number'],
            [['name', 'color'], 'string', 'max' => 255],
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
            'mapId' => 'Map ID',
            'name' => 'Name',
            'color' => 'Color',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
