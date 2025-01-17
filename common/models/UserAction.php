<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_action".
 *
 * @property int $id
 * @property int $userId
 * @property string $uri
 * @property int $unixtime
 * @property int $statuscode
 */
class UserAction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_action';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'uri', 'unixtime', 'statuscode'], 'required'],
            [['userId', 'unixtime', 'statuscode'], 'integer'],
            [['uri'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'uri' => 'Uri',
            'unixtime' => 'Unixtime',
            'statuscode' => 'Statuscode',
        ];
    }
}
