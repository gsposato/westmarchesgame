<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "equipment".
 *
 * @property int $id
 * @property int $campaignId
 * @property int $characterId
 * @property string $name
 * @property int $category
 * @property int $state
 * @property string $description
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class Equipment extends NotarizedModel
{
    public const CATEGORY_SLUMBERING = 1;

    public const STATE_SLUMBERING = 1;
    public const STATE_STIRRING = 2;
    public const STATE_AWAKENED = 3;
    public const STATE_ASCENDANT = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'equipment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'characterId', 'name', 'category', 'state', 'description', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'characterId', 'category', 'state', 'owner', 'creator', 'created', 'updated'], 'integer'],
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
            'characterId' => 'Character ID',
            'name' => 'Name',
            'category' => 'Category',
            'state' => 'State',
            'description' => 'Description',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * Category Select
     */
    public static function categorySelect()
    {
        return array(
            self::CATEGORY_SLUMBERING => "Slumbering"
        );
    }

    /**
     * State Select
     */
    public static function stateSelect()
    {
        return array(
            self::STATE_SLUMBERING => "Slumbering",
            self::STATE_STIRRING => "Stirring",
            self::STATE_AWAKENED => "Awakened",
            self::STATE_ASCENDANT => "Ascendant"
        );
    }
}
