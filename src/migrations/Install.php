<?php
namespace codemonauts\shortener\migrations;

use craft\db\Migration;
use craft\db\Table;

class Install extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%shortener_shortUrls}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string()->notNull(),
            'destination' => $this->string(1024)->notNull(),
            'redirectCode' => $this->integer()->unsigned()->notNull(),
            'templateId' => $this->integer(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        $this->addForeignKey(null, '{{%shortener_shortUrls}}', ['id'], Table::ELEMENTS, ['id'], 'CASCADE', null);
        $this->addForeignKey(null, '{{%shortener_shortUrls}}', ['templateId'], Table::ELEMENTS, ['id'], 'CASCADE', null);
    }

    public function safeDown()
    {
        $this->dropTableIfExists('{{%shortener_shortUrls}}');
    }
}
