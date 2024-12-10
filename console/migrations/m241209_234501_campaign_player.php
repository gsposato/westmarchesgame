<?php

use yii\db\Migration;

class m241209_234501_campaign_player extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table campaign_player(
    id int not null auto_increment,
    campaignId int not null,
    userId int not null,
    isPlayer tinyint(1) not null default 0,
    isHost tinyint(1) not null default 0,
    isAdmin tinyint(1) not null default 0,
    created int not null,
    updated int not null,
    primary key (id)
)
SQL;
    Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
drop table campaign_player
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
