<?php

namespace Hexlet\Code;

use Hexlet\Code\Controller\IndexController;
use Hexlet\Code\Controller\UrlController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class Routes
{
    public static function init(App $app): void
    {
        $app->get('/', 'Hexlet\Code\Controller\IndexController:indexAction')->setName('urls.index');

        // $app->post('/urls', 'Hexlet\Code\Controller\UrlController:createAction')->setName('urls.store');

        $app->group('/urls', function (RouteCollectorProxy $group): void {
            $group->post('', 'Hexlet\Code\Controller\UrlController:createAction')->setName('urls.store');
            $group->get('', 'Hexlet\Code\Controller\UrlController:indexAction')->setName('urls.check');
            $group->get('/{id:\d+}', 'Hexlet\Code\Controller\UrlController:getUrlAction')->setName('urls.show');
        });
    }



    // $group->get('', ...)->setName('urls.index');
    // $group->post('', ...)->setName('urls.store');
    // $group->get('/{id}', ...)->setName('urls.show');
    // $group->post('/{id}/checks', ...)->setName('urls.check');
}
