<?php

namespace codemonauts\shortener\migrations;

use craft\db\Migration;

/**
 * m210628_091722_add_description migration.
 */
class m210628_091722_add_description extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%shortener_shortUrls}}', 'description', $this->string(1024));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shortener_shortUrls}}', 'description');
    }
}
