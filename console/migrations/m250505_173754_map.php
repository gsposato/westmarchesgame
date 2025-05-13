<?php

use yii\db\Migration;

class m250505_173754_map extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table map(
    id int not null auto_increment,
    campaignId int not null,
    name varchar(255) not null,
    image text not null,
    minzoom int(11) not null default 0,
    maxzoom int(11) not null default 0,
    defaultzoom int(11) not null default 0,
    owner int(11) not null,
    creator int(11) not null,
    created int(11) not null,
    updated int(11) not null,
    primary key (id)
)
SQL;
        Yii::$app->db->createCommand($sql)->execute();
        $sql = <<<SQL
create table map_marker(
    id int not null auto_increment,
    campaignId int not null,
    mapId int not null,
    name varchar(255) not null,
    color varchar(255) not null,
    lat float not null,
    lng float(11) not null,
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
drop table map
SQL;
        Yii::$app->db->createCommand($sql)->execute();
        $sql = <<<SQL
drop table map_marker
SQL;
        return Yii::$app->db->createCommand($sql)->execute();
    }
}
