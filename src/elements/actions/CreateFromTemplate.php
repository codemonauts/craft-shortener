<?php

namespace codemonauts\shortener\elements\actions;

use codemonauts\shortener\elements\Template;
use codemonauts\shortener\Shortener;
use Craft;
use craft\base\ElementAction;
use craft\elements\db\ElementQueryInterface;
use yii\base\Exception;

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
    public function getTriggerHtml(): ?string
    {
        $templates = Template::find()->all();

        return Craft::$app->getView()->renderTemplate('shortener/actions/_createFromTemplate', [
            'templates' => $templates,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function performAction(ElementQueryInterface $query): bool
    {
        $request = Craft::$app->getRequest();
        $shortener = Shortener::getInstance()->shortUrl;
        $templateId = $request->getParam('templateId');

        $template = Template::find()
            ->id($templateId)
            ->one();

        if (!$template) {
            $this->setMessage('Cannot find template.');

            return false;
        }

        foreach ($query->all() as $entry) {
            try {
                $shortener->createFromTemplate($entry, $template);
            } catch (Exception $e) {
                $this->setMessage('Not all Short URLs could be created. Error "' . $e->getMessage() . '" for entry with ID ' . $entry->id);

                return false;
            }
        }

        $this->setMessage('Short URLs generated.');

        return true;
    }
}
