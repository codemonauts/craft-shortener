<?php

namespace codemonauts\shortener\elements\db;

use codemonauts\shortener\elements\Template;
use craft\elements\db\ElementQuery;
use yii\db\Connection;

/**
 * Class TemplateQuery
 *
 * @method Template[]|array all($db = null)
 * @method Template|array|null one($db = null)
 * @method Template|array|null nth(int $n, Connection $db = null)
 */
class TemplateQuery extends ElementQuery
{
    /**
     * @inheritDoc
     */
    protected function beforePrepare(): bool
    {
        // join in the products table
        $this->joinElementTable('shortener_templates');

        // select the price column
        $this->query->select([
            'shortener_templates.title',
            'shortener_templates.pattern',
            'shortener_templates.description',
            'shortener_templates.redirectCode',
        ]);

        return parent::beforePrepare();
    }
}
