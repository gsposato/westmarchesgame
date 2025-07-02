<?php

use yii\db\Migration;

class m250701_192913_ticket extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table ticket(
    id int not null auto_increment,
    campaignId int not null,
    name varchar(255) not null,
    status int(11) not null,
    note text not null,
    owner int not null,
    creator int not null,
    created int not null,
    updated int not null,
    primary key (id)
)
SQL;
        Yii::$app->db->createcommand($sql)->execute();
        $sql = <<<SQL
create table ticket_comment(
    id int not null auto_increment,
    campaignId int not null,
    ticketId int not null,
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
drop table ticket
SQL;
        Yii::$app->db->createCommand($sql)->execute();
        $sql = <<<SQL
drop table ticket_comment
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
