<?php

namespace codemonauts\shortener\controllers;

use codemonauts\shortener\elements\ShortUrl;
use codemonauts\shortener\Shortener;
use Craft;
use craft\web\Controller;
use yii\web\NotFoundHttpException;

class ShortUrlController extends Controller
{
    public function beforeAction($action): bool
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $this->requirePermission('shortManage');

        return true;
    }

    public function actionIndex()
    {
        return $this->renderTemplate('shortener/shortUrl/index');
    }

    public function actionEdit(int $shortId = null, ShortUrl $shortUrl = null)
    {
        if ($shortId !== null) {
            if ($shortUrl === null) {
                $shortUrl = ShortUrl::findOne(['id' => $shortId]);
                if (!$shortUrl) {
                    throw new NotFoundHttpException();
                }
            }
        } else {
            if ($shortUrl === null) {
                $shortUrl = new ShortUrl();
            }
        }

        $variables['shortUrl'] = $shortUrl;
        $variables['title'] = 'Edit short URL';
        $variables['continueEditingUrl'] = 'shortener/short-url/{shortId}';
        $variables['isNew'] = !$shortUrl->id;

        $this->renderTemplate('shortener/shortUrl/_edit', $variables);
    }

    public function actionSave()
    {
        $this->requirePostRequest();
        $request = Craft::$app->getRequest();
        $shortId = $request->getBodyParam('shortId');

        if ($shortId) {
            $shortUrl = ShortUrl::findOne(['id' => $shortId]);
        } else {
            $shortUrl = new ShortUrl();
            $shortUrl->setScenario(ShortUrl::SCENARIO_CREATE);
            $shortUrl->code = Shortener::getInstance()->shortUrl->generateUniqueCode();
        }

        $shortUrl->destination = $request->getBodyParam('destination');
        $shortUrl->redirectCode = $request->getBodyParam('redirectCode');

        if (Craft::$app->elements->saveElement($shortUrl)) {
            Craft::$app->session->setName('Short URL saved.');
            return $this->redirectToPostedUrl($shortUrl);
        }

        Craft::$app->session->setError('Short URL not saved.');

        Craft::$app->urlManager->setRouteParams([
            'shortUrl' => $shortUrl,
        ]);

        return null;
    }
}
