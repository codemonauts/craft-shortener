<?php

namespace codemonauts\shortener\elements;

use codemonauts\shortener\elements\db\TemplateQuery;
use codemonauts\shortener\records\Template as TemplateRecord;
use craft\base\Element;
use craft\elements\db\ElementQueryInterface;
use yii\base\Exception;

/**
 * Class Template
 */
class Template extends Element
{
    public $title;
    public $pattern;
    public $description;
    public $redirectCode;

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Template';
    }

    /**
     * @inheritdoc
     */
    public static function lowerDisplayName(): string
    {
        return 'template';
    }

    /**
     * @inheritdoc
     */
    public static function pluralDisplayName(): string
    {
        return 'Templates';
    }

    /**
     * @inheritdoc
     */
    public static function pluralLowerDisplayName(): string
    {
        return 'templates';
    }

    /**
     * @inheritdoc
     */
    public function getIsEditable(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getCpEditUrl(): string
    {
        return 'shortener/template/' . $this->id;
    }

    /**
     * @inheritDoc
     * @return TemplateQuery
     */
    public static function find(): ElementQueryInterface
    {
        return new TemplateQuery(static::class);
    }

    /**
     * @inheritDoc
     */
    public static function defineSources(string $context = null): array
    {
        return [
            [
                'key' => '*',
                'label' => 'All templates',
                'criteria' => [],
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function defineTableAttributes(): array
    {
        return [
            'title' => 'Title',
            'pattern' => 'Pattern',
            'description' => 'Description',
            'redirectCode' => 'Redirect HTTP status',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function defineDefaultTableAttributes(string $source): array
    {
        return [
            'title',
            'pattern',
            'redirectCode',
            'description',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function defineSortOptions(): array
    {
        return [
            'title' => 'Title',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function defineSearchableAttributes(): array
    {
        return [
            'title',
            'description',
        ];
    }

    /**
     * @inheritDoc
     */
    public function tableAttributeHtml(string $attribute): string
    {
        return parent::tableAttributeHtml($attribute);
    }

    /**
     * @inheritDoc
     */
    public function afterSave(bool $isNew): void
    {
        if (!$isNew) {
            $record = TemplateRecord::findOne($this->id);

            if (!$record) {
                throw new Exception('Invalid template ID: ' . $this->id);
            }
        } else {
            $record = new TemplateRecord();
            $record->id = (int)$this->id;
        }

        $record->title = $this->title;
        $record->pattern = $this->pattern;
        $record->description = $this->description;
        $record->redirectCode = $this->redirectCode;

        $record->save(false);

        parent::afterSave($isNew);
    }

    /**
     * @inheritDoc
     */
    public function defineRules(): array
    {
        $rules = parent::defineRules();

        $rules[] = [['title', 'pattern', 'redirectCode'], 'required'];

        return $rules;
    }
}
