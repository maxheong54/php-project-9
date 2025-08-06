<?php

namespace Hexlet\Code;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class Routes
{
    public static function init(App $app): void
    {
        $app->get('/', 'Hexlet\Code\Controller\IndexController:indexAction')->setName('index');

        $app->group('/urls', function (RouteCollectorProxy $group): void {
            $group->post('', 'Hexlet\Code\Controller\UrlController:createAction')
                ->setName('urls.store');

            $group->post('/{id:\d+}/checks', 'Hexlet\Code\Controller\UrlCheckController:checkUrl')
                ->setName('urls.check');

            $group->get('', 'Hexlet\Code\Controller\UrlController:indexAction')
                ->setName('urls.index');

            $group->get('/{id:\d+}', 'Hexlet\Code\Controller\UrlController:getUrlAction')
                ->setName('urls.show');
        });
    }
}
