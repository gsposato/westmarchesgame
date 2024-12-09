<?php

use yii\db\Migration;

class m241204_225750_user_action extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
create table user_action(
    id int not null auto_increment,
    userId int not null,
    uri varchar(255) not null,
    unixtime int not null,
    primary key (id)
)
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
drop table user_action
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
