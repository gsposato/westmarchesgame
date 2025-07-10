<?php

use yii\db\Migration;

class m250710_191842_game_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
alter table game add column category int(11) not null default 1
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
alter table game drop column category
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
