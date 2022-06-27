<?php

namespace codemonauts\shortener\controllers;

use codemonauts\shortener\Shortener;
use craft\web\Controller;
use craft\web\Response;

class RedirectController extends Controller
{
    public $defaultAction = 'redirect';

    public int|bool|array $allowAnonymous = true;

    public function actionRedirect(string $code): Response
    {
        return Shortener::getInstance()->shortUrl->redirect($code);
    }

    public function actionCatchAll(string $path): Response
    {
        return Shortener::getInstance()->shortUrl->handleCatchAll($path);
    }
}
