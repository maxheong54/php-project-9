<?php

namespace Tests;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Slim\Views\Twig;

class IndexControllerTest extends BaseControllerTestCase
{
    public function testIndexAction(): void
    {
        $uri = $this->createConfiguredMock(
            UriInterface::class,
            ['getPath' => '/current/path']
        );

        $request = $this->createConfiguredMock(
            ServerRequestInterface::class,
            ['getUri' => $uri]
        );

        $response = $this->createMock(ResponseInterface::class);

        $view = $this->createMock(Twig::class);
        $view->expects($this->once())
            ->method('render')
            ->with(
                $response,
                'main.html.twig',
                ['currentPath' => '/current/path']
            )
            ->willReturn($response);

        $controller = $this->getController('IndexController', ['view' => $view]);

        $controller->indexAction($request, $response);
    }
}
