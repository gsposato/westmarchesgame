<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "equipment_goal_requirement".
 *
 * @property int $id
 * @property int $campaignId
 * @property int $equipmentGoalId
 * @property string $name
 * @property string $description
 * @property int $progress
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class EquipmentGoalRequirement extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'equipment_goal_requirement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'equipmentGoalId', 'name', 'description', 'progress', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'equipmentGoalId', 'progress', 'owner', 'creator', 'created', 'updated'], 'integer'],
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
            'equipmentGoalId' => 'Equipment Goal ID',
            'name' => 'Name',
            'description' => 'Description',
            'progress' => 'Progress',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
