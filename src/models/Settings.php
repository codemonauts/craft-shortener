<?php

namespace codemonauts\shortener\models;

use craft\base\Model;

class Settings extends Model
{
    /**
     * @var string The domain to use.
     */
    public string $domain = '';

    /**
     * @var bool Whether to activate the catch-all function..
     */
    public bool $catchall = true;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['domain'], 'required'],
        ];
    }
}
