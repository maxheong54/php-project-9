<?php

namespace Hexlet\Code;

use Hexlet\Code\Controller\IndexController;
use Hexlet\Code\Controller\UrlCheckController;
use Hexlet\Code\Controller\UrlController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use DI\Container;

class Routes
{
    /**
     * @param App<Container> $app $name
     */
    public static function init(App $app): void
    {
        $app->get('/', IndexController::class . ':indexAction')->setName('index');

        $app->group('/urls', function (RouteCollectorProxy $group): void {

            $group->post('', UrlController::class . ':createAction')
                ->setName('urls.store');

            $group->post('/{id:\d+}/checks', UrlCheckController::class . ':checkUrl')
                ->setName('urls.check');

            $group->get('', UrlController::class . ':indexAction')

                ->setName('urls.index');

            $group->get('/{id:\d+}', UrlController::class . ':getUrlAction')
                ->setName('urls.show');
        });
    }
}
