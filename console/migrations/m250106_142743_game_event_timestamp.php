<?php

use yii\db\Migration;

class m250106_142743_game_event_timestamp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
alter table campaign_player
add column gameEventTimestamp int(11) not null default 0,
add column gameEventNumber int(11) not null default 0
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
alter table campaign_player
drop column gameEventTimestamp,
drop column gameEventNumber
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
