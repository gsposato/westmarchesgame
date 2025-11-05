<?php

use yii\db\Migration;

class m251105_140812_hibernate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
alter table campaign_player add column hibernated int(11) not null default 0
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
alter table campaign_player drop column hibernated
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
