<?php

use yii\db\Migration;

class m250705_144642_soft_delete extends Migration
{
    public $tables = [
        "campaign",
        "campaign_announcement",
        "campaign_character",
        "campaign_document",
        "campaign_player",
        "currency",
        "equipment",
        "equipment_goal",
        "equipment_goal_requirement",
        "game",
        "game_event",
        "game_note",
        "game_player",
        "game_poll",
        "game_poll_slot",
        "game_summary",
        "map",
        "map_marker",
        "player_complaint",
        "player_trigger",
        "purchase",
        "ticket",
        "ticket_comment"
    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach ($this->tables as $table) {
            $sql = <<<SQL
alter table {$table} add column deleted int(11) not null default 0
SQL;
            Yii::$app->db->createCommand($sql)->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        foreach ($this->tables as $table) {
            $sql = <<<SQL
alter table {$table} drop column deleted 
SQL;
            Yii::$app->db->createCommand($sql)->execute();
        }
    }
}
