<?php

use yii\db\Migration;

class m241204_221448_campaign_announcement extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table campaign_announcement(
    id int not null auto_increment,
    campaignId int not null,
    name varchar(255) not null,
    note text not null,
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
drop table campaign_announcement
SQL;
        return Yii::$app->db->createCommand($sql)->execute();
    }
}
