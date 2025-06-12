<?php

use yii\db\Migration;

class m250607_160540_equipment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table equipment(
    id int not null auto_increment,
    campaignId int not null,
    characterId int not null,
    name varchar(255) not null,
    category int(11) not null,
    state int(11) not null, 
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
create table equipment_goal(
    id int(11) not null auto_increment,
    campaignId int not null,
    equipmentId int not null,
    name varchar(255) not null,
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
create table equipment_goal_requirement(
    id int(11) not null auto_increment,
    campaignId int not null,
    equipmentGoalId int not null,
    name varchar(255) not null,
    description text not null,
    progress int(11) not null,
    owner int(11) not null,
    creator int(11) not null,
    created int(11) not null,
    updated int(11) not null,
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
drop table equipment
SQL;
        Yii::$app->db->createCommand($sql)->execute();
        $sql = <<<SQL
drop table equipment_goal
SQL;
        Yii::$app->db->createCommand($sql)->execute();
        $sql = <<<SQL
drop table equipment_goal_requirement
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
