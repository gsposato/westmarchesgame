<?php

use yii\db\Migration;

class m250715_134951_form extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table form(
    id int not null auto_increment,
    campaignId int not null,
    name varchar(255) not null,
    status int(11) not null,
    note text not null,
    owner int not null,
    creator int not null,
    created int not null,
    updated int not null,
    deleted int not null default 0,
    primary key (id)
)
SQL;
        Yii::$app->db->createcommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
drop table form
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
