<?php

use yii\db\Migration;

class m241129_192926_campaign extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table campaign(
    id int not null auto_increment,
    name varchar(255) not null,
    rules text null,
    owner int(11) not null,
    creator int(11) not null,
    created int(11) not null,
    updated int(11) not null,
    primary key (id)
)
SQL;
        return Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
drop table campaign
SQL;
        return Yii::$app->db->createCommand($sql)->execute();
    }
}
