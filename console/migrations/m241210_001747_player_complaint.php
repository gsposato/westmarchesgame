<?php

use yii\db\Migration;

class m241210_001747_player_complaint extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table player_complaint(
    id int not null auto_increment,
    campaignId int not null,
    gameId int null,
    reportingUserId int not null,
    reportingCharacterId int not null,
    offendingUserId int not null,
    offendingCharacterId int not null,
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
drop table player_complaint
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
