<?php

namespace common\models;

use Yii;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property int $modelId
 * @property string $modelClass
 * @property string $attributeName
 * @property string $attributeValue
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 * @property int $deleted
 */
class Event extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['modelId', 'modelClass', 'attributeName', 'attributeValue', 'owner', 'creator', 'created', 'updated', 'deleted'], 'required'],
            [['modelId', 'owner', 'creator', 'created', 'updated', 'deleted'], 'integer'],
            [['modelClass', 'attributeName', 'attributeValue'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'modelId' => 'Model ID',
            'modelClass' => 'Model Class',
            'attributeName' => 'Attribute Name',
            'attributeValue' => 'Attribute Value',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * Add New Event
     * @param object $model
     * @param string $name
     * @param mixed $value
     */
    public static function add($model, $name, $value)
    {
        if (empty($value)) {
            return;
        }
        if ($model->{$name} == $value) {
            return;
        }
        $ignore = [
            "created",
            "updated",
            "deleted"
        ];
        foreach ($ignore as $attributeName) {
            if ($attributeName == $name) {
                return;
            }
        }
        $event = New Event();
        $event->modelId = $model->id;
        $event->modelClass = StringHelper::basename($model::class);
        $event->attributeName = $name;
        $event->attributeValue = strval($model->{$name});
        $event->owner = Yii::$app->user->identity->id ?? 1;
        $event->deleted = 0;
        if ($event->save()) {
            return $event;
        }
        foreach ($event->getErrors() as $err) {
            throw new \Exception (array_pop($err));
        }
    }
}
