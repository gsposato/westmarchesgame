<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "email".
 *
 * @property int $id
 * @property string $name
 * @property string $result
 * @property float $response
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 * @property int $deleted
 */
class Email extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'email';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'result', 'response', 'owner', 'creator', 'created', 'updated', 'deleted'], 'required'],
            [['result'], 'string'],
            [['response'], 'number'],
            [['owner', 'creator', 'created', 'updated', 'deleted'], 'integer'],
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
            'name' => 'Name',
            'result' => 'Result',
            'response' => 'Response',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
        ];
    }
}
