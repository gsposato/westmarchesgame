<?php

use yii\db\Migration;

class m241204_131749_game_player extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table game_player(
    id int not null auto_increment,
    gameId int not null,
    userId int null,
    characterId int not null,
    status int not null,
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
drop table game_player
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
