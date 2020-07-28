<?php

namespace codemonauts\shortener\elements\db;

use codemonauts\shortener\elements\ShortUrl;
use craft\elements\db\ElementQuery;
use craft\helpers\Db;
use yii\db\Connection;

/**
 * Class ShortUrlQuery
 *
 * @method ShortUrl[]|array all($db = null)
 * @method ShortUrl|array|null one($db = null)
 * @method ShortUrl|array|null nth(int $n, Connection $db = null)
 */
class ShortUrlQuery extends ElementQuery
{
    public $code;
    public $templateId;
    public $redirectCode;

    /**
     * @param string $value The property value
     *
     * @return static self reference
     */
    public function code($value)
    {
        $this->code = (string)$value;

        return $this;
    }

    /**
     * @param int $value The property value
     *
     * @return static self reference
     */
    public function templateId($value)
    {
        $this->templateId = (int)$value;

        return $this;
    }

    /**
     * @param int $value The property value
     *
     * @return static self reference
     */
    public function redirectCode($value)
    {
        $this->redirectCode = (int)$value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function beforePrepare(): bool
    {
        // join in the products table
        $this->joinElementTable('shortener_shortUrls');

        // select the price column
        $this->query->select([
            'shortener_shortUrls.code',
            'shortener_shortUrls.destination',
            'shortener_shortUrls.redirectCode',
            'shortener_shortUrls.templateId',
        ]);

        if ($this->code) {
            $this->subQuery->andWhere(Db::parseParam('shortener_shortUrls.code', $this->code));
        }

        if ($this->redirectCode) {
            $this->subQuery->andWhere(Db::parseParam('shortener_shortUrls.redirectCode', $this->redirectCode));
        }

        if ($this->templateId) {
            $this->subQuery->andWhere(Db::parseParam('shortener_shortUrls.templateId', $this->templateId));
        }

        return parent::beforePrepare();
    }
}
