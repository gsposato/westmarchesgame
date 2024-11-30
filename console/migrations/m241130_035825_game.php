<?php

use yii\db\Migration;

class m241130_035825_game extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table game(
    id int not null auto_increment,
    campaignId int not null,
    name varchar(255) not null,
    levelRange varchar(255) null,
    gameInviteLink text null,
    timeDuration varchar(255) null,
    voiceVenueLink text null,
    goldPayoutPerPlayer int null,
    credit int not null default 1,
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
drop table game
SQL;
        return Yii::$app->db->createCommand($sql)->execute();
    }
}
