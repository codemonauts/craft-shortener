<?php

namespace codemonauts\shortener\controllers;

use codemonauts\shortener\elements\ShortUrl;
use craft\web\Controller;
use yii\web\NotFoundHttpException;

class RedirectController extends Controller
{
    public $defaultAction = 'redirect';

    public $allowAnonymous = true;

    public function actionRedirect(string $code)
    {
        $shortUrl = ShortUrl::find()
            ->code($code)
            ->one();

        if (!$shortUrl) {
            throw new NotFoundHttpException();
        }

        return $this->redirect($shortUrl->destination, $shortUrl->redirectCode);
    }
}
