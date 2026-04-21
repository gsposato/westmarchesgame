<?php

use yii\db\Migration;

class m260420_224706_modify_credit extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
alter table game
modify credit decimal(10,2) not null default 0;
SQL;
        Yii::$app->db->createCommand($sql)->execute();
        $sql = <<<SQL
alter table campaign_character
modify startingCredit decimal(10,2) null;
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
modify credit int(11) not null default 1;
SQL;
        Yii::$app->db->createCommand($sql)->execute();
        $sql = <<<SQL
alter table campaign_character
modify startingCredit int(11) null;
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
