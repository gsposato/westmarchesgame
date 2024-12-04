<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "campaign".
 *
 * @property int $id
 * @property string $name
 * @property string|null $rules
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class Campaign extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'campaign';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['rules'], 'string'],
            [['owner', 'creator', 'created', 'updated'], 'integer'],
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
            'rules' => 'Rules',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
