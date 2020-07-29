<?php

namespace codemonauts\shortener;

use codemonauts\shortener\elements\actions\CreateFromTemplate;
use codemonauts\shortener\services\ShortUrl;
use codemonauts\shortener\services\Template;
use Craft;
use craft\base\Element;
use \craft\base\Plugin;
use craft\elements\Entry;
use craft\events\RegisterElementActionsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterUserPermissionsEvent;
use craft\services\UserPermissions;
use craft\web\UrlManager;
use yii\base\Event;


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
                'shortener:statistics' => ['label' => 'Show statistics'],
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
            $event->rules['shortener/statistics'] = 'shortener/statistics/index';
        });

        // Register element actions
        Event::on(Entry::class, Element::EVENT_REGISTER_ACTIONS, function(RegisterElementActionsEvent $event) {
            $event->actions[] = CreateFromTemplate::class;
        });
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

        if ($currentUser->can('shortener:statistics')) {
            $subNavs['shortStatistics'] = [
                'url' => 'shortener/statistics',
                'label' => 'Statistics',
            ];
        }

        $navItem['subnav'] = $subNavs;

        return $navItem;
    }
}
