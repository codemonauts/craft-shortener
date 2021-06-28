<?php

namespace codemonauts\shortener\migrations;

use craft\db\Migration;
use craft\db\Table;

class Install extends Migration
{
    public function safeUp()
    {
        $this->dropTableIfExists('{{%shortener_shortUrls}}');
        $this->createTable('{{%shortener_shortUrls}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string()->notNull(),
            'destination' => $this->string(1024)->notNull(),
            'redirectCode' => $this->integer()->unsigned()->notNull(),
            'description' => $this->string(1024),
            'templateId' => $this->integer(),
            'elementId' => $this->integer(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        $this->addForeignKey(null, '{{%shortener_shortUrls}}', ['id'], Table::ELEMENTS, ['id'], 'CASCADE', null);

        $this->dropTableIfExists('{{%shortener_templates}}');
        $this->createTable('{{%shortener_templates}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'pattern' => $this->string(1024)->notNull(),
            'description' => $this->string(1024),
            'redirectCode' => $this->integer()->unsigned()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        $this->addForeignKey(null, '{{%shortener_templates}}', ['id'], Table::ELEMENTS, ['id'], 'CASCADE', null);
    }

    public function safeDown()
    {
        $this->dropTableIfExists('{{%shortener_shortUrls}}');
        $this->dropTableIfExists('{{%shortener_templates}}');
    }
}
