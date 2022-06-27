# A URL Shortener for Craft CMS

![Icon](resources/shortener.png)

A URL Shortener for Craft CMS.

## Requirements

- Craft CMS >= 4.0.0

## Installation

Open your terminal and go to your Craft project:

``` shell
cd /path/to/project
composer require codemonauts/craft-shortener
./craft plugin/install shortener
```

You can also install the plugin via the Plugin Store in the Craft Control Panel.

## Configuration

In the settings dialog you have to enter the domain you want to use for shortening. This domain has to point to your Craft CMS server but **does not have to be** configured as site in the Craft CMS. But it **can** be one of your configured domains.

If you use one of your site domains for shortening, please keep in mind that the plugin adds an URL route to Craft which maybe collides with other routes.

The normal case is, that you have different domains for shortening and for your site.

With ❤ by [codemonauts](https://codemonauts.com)
