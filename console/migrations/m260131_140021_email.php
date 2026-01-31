<?php

use yii\db\Migration;

class m260131_140021_email extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table email(
    id int not null auto_increment,
    name varchar(255) not null,
    result text not null,
    response decimal(10,4) not null,
    owner int(11) not null,
    creator int(11) not null,
    created int(11) not null,
    updated int(11) not null,
    deleted int(11) not null,
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
drop table email
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
