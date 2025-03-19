<?php

use yii\db\Migration;

class m250319_125412_add_host_to_game extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
alter table game add column host int(11) null
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
alter table game drop column host
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
