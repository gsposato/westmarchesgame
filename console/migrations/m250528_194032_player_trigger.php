<?php

use yii\db\Migration;

class m250528_194032_player_trigger extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table player_trigger(
    id int not null auto_increment,
    campaignId int not null,
    playerId int not null,
    name varchar(255) not null,
    category int(11) not null,
    description text not null,
    owner int(11) not null,
    creator int(11) not null,
    created int(11) not null,
    updated int(11) not null,
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
drop table player_trigger
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
