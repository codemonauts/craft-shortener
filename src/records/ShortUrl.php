<?php

namespace codemonauts\shortener\records;

use craft\db\ActiveRecord;
use craft\records\Element;
use craft\records\Entry;
use yii\db\ActiveQueryInterface;

/**
 * Class ShortUrl
 *
 * @property int $id ID
 * @property string $code The short code.
 * @property string $destination The destination Url.
 * @property int $redirectCode The redirect HTTP status code to use.
 * @property int $templateId Template ID
 * @property int $elementId Element ID
 * @property Element $element Element
 * @property Element $template Template
 */
class ShortUrl extends ActiveRecord
{
    /**
     * @inheritdoc
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%shortener_shortUrls}}';
    }

    /**
     * Returns the element.
     *
     * @return ActiveQueryInterface The relational query object.
     */
    public function getElement(): ActiveQueryInterface
    {
        return $this->hasOne(Element::class, ['id' => 'id']);
    }

    /**
     * Returns the template related to this Short URL.
     *
     * @return ActiveQueryInterface The relational query object.
     */
    public function getTemplate(): ActiveQueryInterface
    {
        return $this->hasOne(Entry::class, ['id' => 'templateId']);
    }

    /**
     * Returns the element used to created this Short URL.
     *
     * @return ActiveQueryInterface The relational query object.
     */
    public function getReference(): ActiveQueryInterface
    {
        return $this->hasOne(Entry::class, ['id' => 'elementId']);
    }
}
