<?php

namespace codemonauts\shortener\services;

use codemonauts\shortener\elements\ShortUrl as ShortUrlElement;
use codemonauts\shortener\elements\Template;
use Craft;
use craft\base\Component;
use craft\elements\Entry;
use Twig\Error\SyntaxError;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

/**
 * Class ShortUrl
 */
class ShortUrl extends Component
{
    public function generateUniqueCode()
    {
        do {
            $code = $this->_generateCode();
        } while (ShortUrlElement::find()->code($code)->exists());

        return $code;
    }

    private function _generateCode($length = 5, $available_sets = 'lud')
    {
        $sets = [];
        if (strpos($available_sets, 'l') !== false) {
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        }
        if (strpos($available_sets, 'u') !== false) {
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        }
        if (strpos($available_sets, 'd') !== false) {
            $sets[] = '23456789';
        }
        $all = '';
        $code = '';
        foreach ($sets as $set) {
            $code .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++) {
            $code .= $all[array_rand($all)];
        }

        $code = str_shuffle($code);

        return $code;
    }

    public function createFromTemplate(Entry $entry, Template $template)
    {
        $view = Craft::$app->getView();
        try {
            $destination = $view->renderString($template->pattern, [
                'entry' => $entry,
            ]);
        } catch (SyntaxError $e) {
            throw new Exception('Syntax error in template pattern.');
        }

        $shortUrl = new ShortUrlElement();
        $shortUrl->templateId = $template->id;
        $shortUrl->elementId = $entry->id;
        $shortUrl->redirectCode = $template->redirectCode;
        $shortUrl->destination = $destination;
        $shortUrl->code = $this->generateUniqueCode();

        if (!Craft::$app->elements->saveElement($shortUrl)) {
            $errors = $shortUrl->getFirstErrors();
            throw new Exception(array_shift($errors));
        }

        return true;
    }

    public function update(ShortUrlElement $shortUrl)
    {
        $template = $shortUrl->getTemplate();
        $entry = $shortUrl->getElement();

        if (!$entry || !$template) {
            return;
        }

        $view = Craft::$app->getView();
        try {
            $destination = $view->renderString($template->pattern, [
                'entry' => $entry,
            ]);
        } catch (SyntaxError $e) {
            throw new Exception('Syntax error in template pattern.');
        }

        $shortUrl->destination = $destination;

        if (!Craft::$app->elements->saveElement($shortUrl)) {
            $errors = $shortUrl->getFirstErrors();
            throw new Exception(array_shift($errors));
        }
    }

    public function exists(Entry $entry)
    {
        return ShortUrlElement::find()
            ->elementId($entry->id)
            ->exists();
    }

    public function redirect($code)
    {
        $response = Craft::$app->getResponse();

        $shortUrl = ShortUrl::find()
            ->code($code)
            ->one();

        if (!$shortUrl) {
            throw new NotFoundHttpException();
        }

        return $response->redirect($shortUrl->destination, $shortUrl->redirectCode);
    }
}
