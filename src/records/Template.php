<?php

namespace codemonauts\shortener\records;

use craft\db\ActiveRecord;
use craft\records\Element;
use yii\db\ActiveQueryInterface;

/**
 * Class Template
 *
 * @property int $id ID
 * @property string $title The title of the template.
 * @property string $pattern The pattern for the destination URL.
 * @property string $description Description of the template.
 * @property int $redirectCode The redirect HTTP status to use.
 * @property Element $element Element
 */
class Template extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%shortener_templates}}';
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
