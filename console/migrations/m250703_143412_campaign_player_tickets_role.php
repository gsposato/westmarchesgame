<?php

use yii\db\Migration;

class m250703_143412_campaign_player_tickets_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
alter table campaign_player
add column isSupport tinyint(1) not null default 0,
add column isSubscribed tinyint(1) not null default 0
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
drop column isSupport,
drop column isSubscribed
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
