<?php

namespace codemonauts\shortener\services;

use codemonauts\shortener\elements\Template as TemplateElement;
use craft\base\Component;
use craft\helpers\ArrayHelper;

/**
 * Class ShortUrl
 */
class Template extends Component
{
    private $_templates;

    public function getAllTemplates()
    {
        if ($this->_templates === null) {
            $this->_templates = TemplateElement::find()->all();
        }

        return $this->_templates;
    }

    public function getTemplateById(int $templateId)
    {
        return ArrayHelper::firstWhere($this->getAllTemplates(), 'id', $templateId);
    }
}
