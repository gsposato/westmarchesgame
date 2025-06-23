<?php

use yii\db\Migration;

class m250623_105920_currency extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table currency(
    id int not null auto_increment,
    campaignId int not null,
    name varchar(255) not null,
    color varchar(255) not null, 
    description text not null,
    owner int(11) not null,
    creator int(11) not null,
    created int(11) not null,
    updated int(11) not null,
    primary key (id)
)
SQL;
        Yii::$app->db->createCommand($sql)->execute();
        $sql = <<<SQL
alter table currency auto_increment = 4;
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
drop table currency
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
