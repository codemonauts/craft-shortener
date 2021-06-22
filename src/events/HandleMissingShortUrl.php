<?php

namespace shortener\events;

use yii\base\Event;

class HandleMissingShortUrl extends Event
{
    public $destination = null;

    public $redirectCode = 302;
}
