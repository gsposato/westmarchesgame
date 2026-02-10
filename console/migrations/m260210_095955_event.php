<?php

use yii\db\Migration;

class m260210_095955_event extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table event(
    id int primary key auto_increment,
    modelId int not null,
    modelClass varchar(255) not null,
    attributeName varchar(255) not null,
    attributeValue varchar(255) not null,
    owner int not null,
    creator int not null,
    created int not null,
    updated int not null,
    deleted int not null
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
drop table event
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
