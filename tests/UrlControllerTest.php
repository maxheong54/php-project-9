<?php

namespace Tests;

use Hexlet\Code\Url;
use Hexlet\Code\UrlRepository;
use Hexlet\Code\UrlValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;

class UrlControllerTest extends BaseControllerTestCase
{
    private ResponseInterface $response;
    private ServerRequestInterface $request;

    public function setUp(): void
    {
        $this->response = $this->createMock(ResponseInterface::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
    }

    public function testIndexAction(): void
    {
        $view = $this->createMock(Twig::class);
        $view->expects($this->once())
            ->method('render')
            ->with(
                $this->response,
                'urls.html.twig',
                $this->isType('array')
            );

        $controller = $this->getController(
            'UrlController',
            ['view' => $view]
        );
        $result = $controller->indexAction($this->request, $this->response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testCreateActionSucces(): void
    {
        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['url' => ['name' => 'https://example.com']]);

        $urlValidator = $this->createConfiguredMock(
            UrlValidator::class,
            ['validateUrl' => []]
        );

        $router = $this->createConfiguredMock(
            RouteParserInterface::class,
            ['urlFor' => 'urls.show']
        );

        $this->response->expects($this->once())
            ->method('withHeader')
            ->with(
                'Location',
                'urls.show'
            )->willReturnSelf();

        $this->response->expects($this->once())
            ->method('withStatus')
            ->with(303);

        $controller = $this->getController(
            'UrlController',
            [
                'UrlValidator' => $urlValidator,
                'router' => $router
            ]
        );

        $result = $controller->createAction($this->request, $this->response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testCreateActionField(): void
    {
        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['url' => ['name' => 'https/example.com']]);

        $urlValidator = $this->createConfiguredMock(
            UrlValidator::class,
            ['validateUrl' => ['Invalid url']]
        );

        $view = $this->createMock(Twig::class);
        $view->expects($this->once())
            ->method('render')
            ->with(
                $this->response,
                'main.html.twig',
                $this->isType('array')
            );

        $this->response->expects($this->once())
            ->method('withStatus')
            ->with(422)
            ->willReturn($this->response);

        $controller = $this->getController(
            'UrlController',
            [
                'UrlValidator' => $urlValidator,
                'view' => $view
            ]
        );

        $result = $controller->createAction($this->request, $this->response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testGetUrlActionSuccess(): void
    {
        $urlRepository = $this->createConfiguredMock(
            UrlRepository::class,
            ['find' => Url::fromArray(['name' => 'https://example.com'])]
        );

        $view = $this->createMock(Twig::class);
        $view->expects($this->once())
            ->method('render')
            ->with(
                $this->response,
                'url.html.twig',
                $this->isType('array')
            );

        $controller = $this->getController(
            'UrlController',
            [
                'urlRepository' => $urlRepository,
                'view' => $view
            ]
        );

        $result = $controller->getUrlAction($this->request, $this->response, ['id' => 1]);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testGetUrlActionField(): void
    {
        $urlRepository = $this->createConfiguredMock(
            UrlRepository::class,
            ['find' => null]
        );

        $this->response->expects($this->once())
            ->method('withStatus')
            ->with(404)
            ->willReturnSelf();

        $view = $this->createMock(Twig::class);
        $view->expects($this->once())
            ->method('render')
            ->with(
                $this->response,
                '404.html.twig'
            );

        $controller = $this->getController(
            'UrlController',
            [
                'urlRepository' => $urlRepository,
                'view' => $view
            ]
        );

        $result = $controller->getUrlAction($this->request, $this->response, ['id' => 1]);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }
}
