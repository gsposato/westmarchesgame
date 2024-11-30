<?php

use yii\db\Migration;

class m241130_124216_game_note extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table game_note(
    id int not null auto_increment,
    gameId int not null,
    note text not null,
    pinned tinyint(1) not null default 0,
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
drop table game_note
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }

}
