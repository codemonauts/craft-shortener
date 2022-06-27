<?php

namespace codemonauts\shortener\elements\db;

use codemonauts\shortener\elements\ShortUrl;
use codemonauts\shortener\elements\Template;
use craft\base\ElementInterface;
use craft\elements\db\ElementQuery;
use craft\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
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
    /**
     * @var mixed The short codes the result must be in.
     */
    public mixed $code = null;

    /**
     * @var mixed The template element ID(s) the results must belong to.
     */
    public mixed $templateId = null;

    /**
     * @var mixed The element ID(s) the results must belong to.
     */
    public mixed $elementId = null;

    /**
     * @var mixed The redirect codes the results must be in.
     */
    public mixed $redirectCode = null;

    /**
     * Narrows the query results based on the short code the short URL belongs to.
     *
     * @param array|string|null $value The property value
     *
     * @return self self reference
     */
    public function code(array|string|null $value): self
    {
        $this->code = $value;

        return $this;
    }

    /**
     * Sets the [[templateId()]] parameter based on a given template element.
     *
     * @param Template $template
     *
     * @return self self reference
     */
    public function template(Template $template): self
    {
        $this->templateId = [$template->id];

        return $this;
    }

    /**
     * Narrows the query results based on the template’ elements, per their IDs.
     *
     * @param array|int|null $value The property value
     *
     * @return self self reference
     */
    public function templateId(array|int|null $value): self
    {
        $this->templateId = $value;

        return $this;
    }

    /**
     * Sets the [[elementId()]] parameter based on a given element.
     *
     * @param ElementInterface $element
     *
     * @return self self reference
     */
    public function element(ElementInterface $element): self
    {
        $this->elementId = [$element->id];

        return $this;
    }

    /**
     * Narrows the query results based on the elements, per their IDs.
     *
     * @param array|int|null $value The property value
     *
     * @return self self reference
     */
    public function elementId(array|int|null $value): self
    {
        $this->elementId = $value;

        return $this;
    }

    /**
     * Narrows the query results based on the redirect code the short URL belongs to.
     *
     * @param array|int|null $value The property value
     *
     * @return self self reference
     */
    public function redirectCode(array|int|null $value): self
    {
        $this->redirectCode = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function beforePrepare(): bool
    {
        $this->normalizeTemplateId();
        $this->normalizeElementId();

        $this->joinElementTable('shortener_shortcodes');

        $this->query->select([
            'shortener_shortcodes.code',
            'shortener_shortcodes.destination',
            'shortener_shortcodes.redirectCode',
            'shortener_shortcodes.description',
            'shortener_shortcodes.templateId',
            'shortener_shortcodes.elementId',
        ]);

        if ($this->code) {
            $this->subQuery->andWhere(['shortener_shortcodes.code' => $this->code]);
        }

        if ($this->redirectCode) {
            $this->subQuery->andWhere(['shortener_shortcodes.redirectCode' => $this->redirectCode]);
        }

        if ($this->templateId) {
            $this->subQuery->andWhere(['shortener_shortcodes.templateId' => $this->templateId]);
        }

        if ($this->elementId) {
            $this->subQuery->andWhere(['shortener_shortcodes.elementId' => $this->elementId]);
        }

        return parent::beforePrepare();
    }

    /**
     * Normalizes the templateId param to an array of IDs or null
     */
    private function normalizeTemplateId(): void
    {
        if ($this->templateId === ':empty:' || $this->templateId === null) {
            return;
        }

        if (is_numeric($this->templateId)) {
            $this->templateId = [$this->templateId];
        }

        if (!is_array($this->templateId) || !ArrayHelper::isNumeric($this->templateId)) {
            throw new InvalidConfigException();
        }
    }

    /**
     * Normalizes the elementId param to an array of IDs or null
     */
    private function normalizeElementId(): void
    {
        if ($this->elementId === ':empty:' || $this->elementId === null) {
            return;
        }

        if (is_numeric($this->elementId)) {
            $this->elementId = [$this->elementId];
        }

        if (!is_array($this->elementId) || !ArrayHelper::isNumeric($this->elementId)) {
            throw new InvalidConfigException();
        }
    }
}
