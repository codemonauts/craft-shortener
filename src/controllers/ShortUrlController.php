<?php

namespace codemonauts\shortener\controllers;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
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

        $this->requirePermission('shortener:urls');

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

        // Generate QR Code if not new
        if ($shortUrl->id) {
            $path = Craft::$app->path->getAssetSourcesPath();
            $options = new QROptions([
                'version'    => 5,
                'outputType' => QRCode::OUTPUT_MARKUP_SVG,
                'eccLevel'   => QRCode::ECC_L,
            ]);

            $qrcode = new QRCode($options);
            $variables['qrcode'] = $qrcode->render($shortUrl->getUrl(), $path . DIRECTORY_SEPARATOR . $shortUrl->code . '.svg');
        }

        $variables['shortUrl'] = $shortUrl;
        $variables['title'] = $shortUrl->id ? 'Edit Short URL' : 'Create Short URL';
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
        $shortUrl->description = $request->getBodyParam('description');

        if (Craft::$app->elements->saveElement($shortUrl)) {
            Craft::$app->session->setNotice('Short URL saved.');

            return $this->redirectToPostedUrl($shortUrl);
        }

        Craft::$app->session->setError('Short URL not saved.');

        Craft::$app->urlManager->setRouteParams([
            'shortUrl' => $shortUrl,
        ]);

        return null;
    }

    public function actionDelete()
    {
        $this->requirePostRequest();

        $shortId = Craft::$app->getRequest()->getRequiredBodyParam('shortId');
        $shortUrl = ShortUrl::findOne(['id' => $shortId]);
        if ($shortUrl === null) {
            throw new NotFoundHttpException('Short URL not found.');
        }

        if (!Craft::$app->getElements()->deleteElement($shortUrl)) {
            Craft::$app->getSession()->setError('Couldn’t delete Short URL.');

            Craft::$app->getUrlManager()->setRouteParams([
                'shortUrl' => $shortUrl,
            ]);

            return null;
        }

        Craft::$app->getSession()->setNotice('Short URL deleted.');

        return $this->redirectToPostedUrl($shortUrl);
    }
}
