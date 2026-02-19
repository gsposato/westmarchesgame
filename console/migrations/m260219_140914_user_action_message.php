<?php

use yii\db\Migration;

class m260219_140914_user_action_message extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
alter table user_action
add column message text null
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
alter table user_action
drop column message
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
