<?php

use yii\db\Migration;

class m241203_154110_game_event extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table game_event(
    id int not null auto_increment,
    gameId int not null,
    gamePollSlotId int not null,
    note text not null,
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
drop table game_event
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
