<?php

namespace codemonauts\shortener\migrations;

use craft\db\Migration;
use craft\db\Table;

/**
 * m220615_232017_rename_table migration.
 */
class m220615_232017_rename_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%shortener_shortcodes}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string()->notNull(),
            'destination' => $this->string(1024)->notNull(),
            'redirectCode' => $this->integer()->unsigned()->notNull(),
            'templateId' => $this->integer(),
            'elementId' => $this->integer(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            'description' => $this->string(1024),
        ]);

        $this->addForeignKey(null, '{{%shortener_shortcodes}}', ['id'], Table::ELEMENTS, ['id'], 'CASCADE', null);

        $this->db->createCommand('insert into shortener_shortcodes select * from shortener_shortUrls')->execute();
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTableIfExists('{{%shortener_shortcodes}}');
    }
}
