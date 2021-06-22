<?php

namespace codemonauts\shortener;

use codemonauts\shortener\elements\actions\CreateFromTemplate;
use codemonauts\shortener\elements\ShortUrl as ShortUrlElement;
use codemonauts\shortener\elements\Template as TemplateElement;
use codemonauts\shortener\models\Settings;
use codemonauts\shortener\services\ShortUrl;
use codemonauts\shortener\services\Template;
use Craft;
use craft\base\Element;
use \craft\base\Plugin;
use craft\elements\Entry;
use craft\events\ModelEvent;
use craft\events\RegisterElementActionsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterUserPermissionsEvent;
use craft\helpers\ElementHelper;
use craft\helpers\UrlHelper;
use craft\services\UserPermissions;
use craft\web\UrlManager;
use codemonauts\shortener\jobs\UpdateShortUrl;
use yii\base\Event;
use yii\web\NotFoundHttpException;


/**
 * Class Shortener
 *
 * @property ShortUrl $shortUrl
 * @property Template $template
 */
class Shortener extends Plugin
{
    /**
     * @inheritDoc
     */
    public $hasCpSettings = true;

    public $hasCpSection = true;

    /**
     * @inheritDoc
     */
    public $schemaVersion = '1.0';

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        // Get settings
        $settings = $this->getSettings();

        // Check for root path in domain
        $domain = trim(Craft::parseEnv($settings->domain), '/');
        $request = Craft::$app->getRequest();
        if ($request->isSiteRequest && stripos($request->hostInfo, $domain) !== false && $request->getUrl() === '/') {
            throw new NotFoundHttpException();
        }

        // Register components
        $this->components = [
            'shortUrl' => ShortUrl::class,
            'template' => Template::class,
        ];

        // Register permissions
        Event::on(UserPermissions::class, UserPermissions::EVENT_REGISTER_PERMISSIONS, function(RegisterUserPermissionsEvent $event) {
            $event->permissions['shortener'] = [
                'shortener:urls' => ['label' => 'Manage Short Urls'],
                'shortener:templates' => ['label' => 'Manage templates'],
            ];
        });

        // Register CP routes
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function(RegisterUrlRulesEvent $event) {
            $event->rules['shortener/short-urls'] = 'shortener/short-url/index';
            $event->rules['shortener/short-url/new'] = 'shortener/short-url/edit';
            $event->rules['shortener/short-url/<shortId:\d+>'] = 'shortener/short-url/edit';
            $event->rules['shortener/templates'] = 'shortener/template/index';
            $event->rules['shortener/template/new'] = 'shortener/template/edit';
            $event->rules['shortener/template/<templateId:\d+>'] = 'shortener/template/edit';
        });

        // Register site routes
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_SITE_URL_RULES, function(RegisterUrlRulesEvent $event) use ($settings, $domain) {
            if ($domain !== '') {
                $event->rules[$domain . '/<code:\w+>'] = 'shortener/redirect';
                $event->rules[$domain . '<path:.*>'] = 'shortener/redirect/catch-all';
            }
        });

        // Register element actions
        Event::on(Entry::class, Element::EVENT_REGISTER_ACTIONS, function(RegisterElementActionsEvent $event) {
            $event->actions[] = CreateFromTemplate::class;
        });

        // Register event handler
        Event::on(Entry::class, Entry::EVENT_AFTER_SAVE, function(ModelEvent $event) {
            /**
             * @var Entry $entry
             */
            $entry = $event->sender;

            if (ElementHelper::isDraftOrRevision($entry)) {
                return;
            }

            if (Shortener::getInstance()->shortUrl->exists($entry)) {
                Craft::$app->queue->push(new UpdateShortUrl([
                    'entryIds' => [$entry->id],
                ]));
            }
        });

        Event::on(TemplateElement::class, TemplateElement::EVENT_AFTER_SAVE, function(ModelEvent $event) {
            /**
             * @var TemplateElement $template
             */
            $template = $event->sender;

            $entries = ShortUrlElement::find()->templateId($template->id)->ids();

            Craft::$app->queue->push(new UpdateShortUrl([
                'entryIds' => $entries,
            ]));
        });
    }

    /**
     * @inheritDoc
     */
    public function afterInstall()
    {
        parent::afterInstall();

        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            return;
        }

        Craft::$app->getResponse()->redirect(
            UrlHelper::cpUrl('settings/plugins/shortener')
        )->send();
    }

    /**
     * @inheritDoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritDoc
     */
    protected function settingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('shortener/settings', [
                'settings' => $this->getSettings(),
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getCpNavItem()
    {
        $request = Craft::$app->getRequest();
        $navItem = parent::getCpNavItem();
        if ($request->getSegment(1) !== 'shortener') {
            $navItem['url'] = 'shortener/short-urls';
        }
        $subNavs = [];

        $currentUser = Craft::$app->getUser()->getIdentity();

        if ($currentUser->can('shortener:urls')) {
            $subNavs['shortShortUrls'] = [
                'url' => 'shortener/short-urls',
                'label' => 'Short URLs',
            ];
        }

        if ($currentUser->can('shortener:templates')) {
            $subNavs['shortTemplates'] = [
                'url' => 'shortener/templates',
                'label' => 'Templates',
            ];
        }

        $navItem['subnav'] = $subNavs;

        return $navItem;
    }
}
