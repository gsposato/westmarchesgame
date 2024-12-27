<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "game_poll_slot".
 *
 * @property int $id
 * @property int $gamePollId
 * @property string $humantime
 * @property string $timezone
 * @property int $unixtime
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class GamePollSlot extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game_poll_slot';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gamePollId', 'humantime', 'timezone', 'unixtime', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['gamePollId', 'unixtime', 'owner', 'creator', 'created', 'updated'], 'integer'],
            [['humantime'], 'safe'],
            [['timezone'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gamePollId' => 'Game Poll ID',
            'humantime' => 'Humantime',
            'timezone' => 'Timezone',
            'unixtime' => 'Unixtime',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    public function makeUnixTime()
    {
        date_default_timezone_set($this->timezone);
        $str = $this->humantime;
        $unixtime = strtotime($str);
        $this->unixtime = $unixtime;
    }
}
