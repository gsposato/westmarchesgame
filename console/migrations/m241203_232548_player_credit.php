<?php

use yii\db\Migration;

class m241203_232548_player_credit extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table player_credit(
    id int not null auto_increment,
    campaignId int not null,
    userId int not null,
    gameId int null,
    category int not null,
    amount decimal(10,4) not null,
    note text null,
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
drop table player_credit
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
