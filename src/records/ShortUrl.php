<?php

namespace codemonauts\shortener\records;

use craft\db\ActiveRecord;
use craft\records\Element;
use yii\db\ActiveQueryInterface;

/**
 * Class ShortUrl
 *
 * @property int $id ID
 * @property string $code The short code.
 * @property string $destination The destination Url.
 * @property int $redirectCode The redirect HTTP status code to use.
 * @property string $description A description to remember the use case.
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
        return '{{%shortener_shortcodes}}';
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
}
