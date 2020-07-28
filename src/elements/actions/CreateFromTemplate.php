<?php

namespace codemonauts\shortener\elements\actions;

use Craft;
use craft\base\ElementAction;
use craft\elements\db\ElementQueryInterface;
use craft\helpers\DateTimeHelper;

/**
 * Create Short URL from template
 */
class CreateFromTemplate extends ElementAction
{
    /**
     * @inheritdoc
     */
    public function getTriggerLabel(): string
    {
        return 'Create Short URL from template';
    }

    /**
     * @inheritdoc
     */
    public function getTriggerHtml()
    {
        return parent::getTriggerHtml(); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public static function isDestructive(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getConfirmationMessage(): string
    {
        return 'Sollen die markierten Produkte des Tages wirklich gelöscht werden?';
    }

    /**
     * @inheritdoc
     */
    public function performAction(ElementQueryInterface $query): bool
    {
    }
}
