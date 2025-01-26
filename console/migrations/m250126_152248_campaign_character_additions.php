<?php

use yii\db\Migration;

class m250126_152248_campaign_character_additions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
alter table campaign_character
add column startingGold int(11) null,
add column startingBastionPoints int(11) null,
add column startingCredit int(11) null,
add column firstGamePlayed int(11) null
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
alter table campaign_character
drop column startingGold,
drop column startingBastionPoints,
drop column startingCredit,
drop column firstGamePlayed
SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
