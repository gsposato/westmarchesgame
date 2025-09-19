<?php

use yii\db\Migration;

class m250919_192419_campaign_character_extra extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
alter table campaign_character add column extra text null
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
alter table campaign_character drop column extra
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }

}
