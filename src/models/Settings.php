<?php

namespace codemonauts\shortener\models;

use craft\base\Model;

class Settings extends Model
{
    /**
     * @var string The domain to use.
     */
    public $domain = '';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['domain'], 'required'],
            [['domain'], 'url'],
        ];
    }
}
