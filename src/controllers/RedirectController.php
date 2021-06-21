<?php

namespace codemonauts\shortener\controllers;

use codemonauts\shortener\Shortener;
use craft\web\Controller;

class RedirectController extends Controller
{
    public $defaultAction = 'redirect';

    public $allowAnonymous = true;

    public function actionRedirect(string $code)
    {
        return Shortener::getInstance()->shortUrl->redirect($code);
    }
}
