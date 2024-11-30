<?php

use yii\db\Migration;

class m241130_032244_document extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table document(
    id int not null auto_increment,
    campaignId int not null,
    name varchar(255) not null,
    url text not null,
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
drop table document
SQL;
        return Yii::$app->db->createCommand($sql)->execute();
    }
}
