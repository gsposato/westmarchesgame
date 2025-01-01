<?php

use yii\db\Migration;

class m241203_213922_campaign_character extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table campaign_character(
    id int not null auto_increment,
    campaignId int not null,
    playerId int not null,
    name varchar(255) not null,
    type int not null,
    status int not null,
    description text null,
    owner int not null,
    creator int not null,
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
drop table campaign_character;
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
