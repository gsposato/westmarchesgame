<?php

use yii\db\Migration;

class m241201_125804_game_poll_slot extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table game_poll_slot(
    id int not null auto_increment,
    gamePollId int not null,
    humantime datetime not null,
    timezone varchar(255) not null,
    unixtime int not null,
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
drop table game_poll_slot
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
