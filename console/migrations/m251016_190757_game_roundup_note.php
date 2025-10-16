<?php

use yii\db\Migration;

class m251016_190757_game_roundup_note extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
alter table game add column gameRoundupNote text null
SQL;
        return Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
alter table game drop column gameRoundupNote
SQL;
        return Yii::$app->db->createCommand($sql)->execute();
    }
}
