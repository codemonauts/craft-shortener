<?php

namespace codemonauts\shortener;

use codemonauts\shortener\services\ShortUrl;
use Craft;
use \craft\base\Plugin;
use craft\events\RegisterCpNavItemsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterUserPermissionsEvent;
use craft\services\UserPermissions;
use craft\web\twig\variables\Cp;
use craft\web\UrlManager;
use yii\base\Event;


/**
 * Class Shortener
 *
 * @property ShortUrl $shortUrl
 */
class Shortener extends Plugin
{
    /**
     * @inheritDoc
     */
    public $hasCpSettings = true;

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
        ];

        // Register permissions
        Event::on(UserPermissions::class, UserPermissions::EVENT_REGISTER_PERMISSIONS, function(RegisterUserPermissionsEvent $event) {
            $event->permissions['Link Shortener'] = [
                'shortManage' => ['label' => 'Manage ShortUrls'],
                'shortStatistics' => ['label' => 'Show statistics'],
            ];
        });

        // Modify CP Navigation
        Event::on(Cp::class, Cp::EVENT_REGISTER_CP_NAV_ITEMS, function(RegisterCpNavItemsEvent $event) {
            if (Craft::$app->user->checkPermission('dms')) {
                $subNavs = [];
                if (Craft::$app->user->checkPermission('shortManage')) {
                    $subNavs['shortManage'] = [
                        'url' => 'shortener/short-urls',
                        'label' => 'Short URLs',
                    ];
                }

                if (Craft::$app->user->checkPermission('shortStatistics')) {
                    $subNavs['shortStatistics'] = [
                        'url' => 'shortener/statistics',
                        'label' => 'Statistics',
                    ];
                }

                $event->navItems[] = [
                    'url' => 'shortener/short-urls',
                    'label' => 'Link Shortener',
                    'subnav' => $subNavs,
                    // 'icon' => '@ns/prefix/path/to/icon.svg',
                ];
            }
        });

        // Register CP routes
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function(RegisterUrlRulesEvent $event) {
            $event->rules['shortener/short-urls'] = 'shortener/short-url/index';
            $event->rules['shortener/short-url/new'] = 'shortener/short-url/edit';
            $event->rules['shortener/short-url/<shortId:\d+>'] = 'shortener/short-url/edit';
            $event->rules['shortener/statistics'] = 'shortener/statistics/index';
        });
    }
}
