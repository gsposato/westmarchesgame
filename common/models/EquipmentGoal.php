<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "equipment_goal".
 *
 * @property int $id
 * @property int $campaignId
 * @property int $equipmentId
 * @property string $name
 * @property string $description
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class EquipmentGoal extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'equipment_goal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'equipmentId', 'name', 'description', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'equipmentId', 'owner', 'creator', 'created', 'updated'], 'integer'],
            [['description'], 'string'],
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
            'equipmentId' => 'Equipment ID',
            'name' => 'Name',
            'description' => 'Description',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
