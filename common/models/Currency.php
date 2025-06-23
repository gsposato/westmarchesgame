<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "currency".
 *
 * @property int $id
 * @property int $campaignId
 * @property string $name
 * @property int $color
 * @property string $description
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class Currency extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'name', 'color', 'description', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'owner', 'creator', 'created', 'updated'], 'integer'],
            [['description'], 'string'],
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
            'name' => 'Name',
            'color' => 'Color',
            'description' => 'Description',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    public function beforeValidate()
    {
        switch ($this->name) {
            case "gold":
            case "golds":
            case "credit":
            case "credits":
            case "bastion point":
            case "bastion points":
                $this->name .= "-".uniqid();
                break;
            default:
                // do nothing
        }
        return parent::beforeValidate();
    }
}
