<?php

namespace codemonauts\shortener\elements;

use codemonauts\shortener\elements\db\ShortUrlQuery;
use codemonauts\shortener\records\ShortUrl as ShortUrlRecord;
use craft\base\Element;
use craft\elements\db\ElementQueryInterface;
use yii\base\Exception;

/**
 * Class ShortUrl
 */
class ShortUrl extends Element
{
    const SCENARIO_CREATE = 'create';

    public $code;
    public $redirectCode;
    public $destination;
    public $templateId;

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->code;
    }

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Short URL';
    }

    /**
     * @inheritdoc
     */
    public static function lowerDisplayName(): string
    {
        return 'short url';
    }

    /**
     * @inheritdoc
     */
    public static function pluralDisplayName(): string
    {
        return 'Short URLs';
    }

    /**
     * @inheritdoc
     */
    public static function pluralLowerDisplayName(): string
    {
        return 'short urls';
    }

    /**
     * @inheritdoc
     */
    public function getIsEditable(): bool
    {
        return !$this->templateId;
    }

    public function getCpEditUrl()
    {
        return 'short-url/'.$this->id;
    }

    /**
     * @inheritDoc
     * @return ShortUrlQuery
     */
    public static function find(): ElementQueryInterface
    {
        return new ShortUrlQuery(static::class);
    }

    /**
     * @inheritDoc
     */
    public static function defineSources(string $context = null): array
    {
        return [
            [
                'key' => '*',
                'label' => 'All classes',
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
            'code' => 'Code',
            'destination' => 'Destination URL',
            'redirectCode' => 'Redirect HTTP status',
            'templateId' => 'Template',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function defineDefaultTableAttributes(string $source): array
    {
        return [
            'code',
            'destination',
            'redirectCode',
            'templateId',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function defineSortOptions(): array
    {
        return [
            'code' => 'Code',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function defineSearchableAttributes(): array
    {
        return [
            'code',
            'destination',
            'redirectCode',
            'templateId',
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
            $record = ShortUrlRecord::findOne($this->id);

            if (!$record) {
                throw new Exception('Invalid short URL ID: ' . $this->id);
            }
        } else {
            $record = new ShortUrlRecord();
            $record->id = (int)$this->id;
        }

        $record->code = $this->code;
        $record->destination = $this->destination;
        $record->redirectCode = $this->redirectCode;
        $record->templateId = $this->templateId;

        $record->save(false);

        parent::afterSave($isNew);
    }

    /**
     * @inheritDoc
     */
    public function defineRules(): array
    {
        $rules = parent::defineRules();

        $rules[] = [['code', 'destination'], 'required'];
        $rules[] = ['code', 'unique', 'targetClass' => ShortUrlRecord::class, 'on' => self::SCENARIO_CREATE];

        return $rules;
    }
}
