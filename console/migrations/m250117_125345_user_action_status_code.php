<?php

use yii\db\Migration;

class m250117_125345_user_action_status_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
alter table user_action
add column statuscode int(11) not null
SQL;
        return Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
alter table user_action
drop column statuscode
SQL;
        return Yii::$app->db->createCommand($sql)->execute();
    }

}
