<?php

use yii\db\Migration;

class m250119_173017_bastion extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
alter table game
add column baseBastionPointsPerPlayer int(11) null,
add column bonusBastionPointsPerPlayer int(11) null
SQL;
        Yii::$app->db->createCommand($sql)->execute();
        $sql = <<<SQL
alter table campaign_character
add column bastionName text null,
add column bastionType text null 
SQL;
        Yii::$app->db->createCommand($sql)->execute();
        $sql = <<<SQL
alter table game_player
add column hasBonusPoints tinyint(1) null
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
alter table game
drop column baseBastionPointsPerPlayer,
drop column bonusBastionPointsPerPlayer
SQL;
        Yii::$app->db->createCommand($sql)->execute();
        $sql = <<<SQL
alter table campaign_character
drop column bastionName,
drop column bastionType
SQL;
        Yii::$app->db->createCommand($sql)->execute();
        $sql = <<<SQL
alter table game_player
drop column hasBonusPoints
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
