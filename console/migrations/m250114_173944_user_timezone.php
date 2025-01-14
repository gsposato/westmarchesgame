<?php

use yii\db\Migration;

class m250114_173944_user_timezone extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
alter table user add column timezone varchar(255) null
SQL;
        return Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
alter table user drop column timezone
SQL;
        return Yii::$app->db->createCommand($sql)->execute();
    }
}
