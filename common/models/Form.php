<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "form".
 *
 * @property int $id
 * @property int $campaignId
 * @property string $name
 * @property int $status
 * @property string $note
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 * @property int $deleted
 */
class Form extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'name', 'status', 'note', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'status', 'owner', 'creator', 'created', 'updated', 'deleted'], 'integer'],
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
            'status' => 'Status',
            'note' => 'Note',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
        ];
    }
}
