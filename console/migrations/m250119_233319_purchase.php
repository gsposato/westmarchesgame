<?php

use yii\db\Migration;

class m250119_233319_purchase extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table purchase(
    id int(11) not null auto_increment,
    name varchar(255) not null,
    campaignId int(11) not null,
    characterId int(11) not null,
    currency tinyint(1) not null,
    price int(11) not null,
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
drop table purchase
SQL;
        return Yii::$app->db->createCommand($sql)->execute();
    }
}
