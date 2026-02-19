<?php

use yii\db\Migration;

class m260219_133528_alter_event extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
ALTER TABLE event
    MODIFY modelClass TEXT NOT NULL,
    MODIFY attributeName TEXT NOT NULL,
    MODIFY attributeValue TEXT NOT NULL
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
ALTER TABLE event
    MODIFY modelClass VARCHAR(255) NOT NULL,
    MODIFY attributeName VARCHAR(255) NOT NULL,
    MODIFY attributeValue VARCHAR(255) NOT NULL
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
