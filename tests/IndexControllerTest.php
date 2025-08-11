<?php

namespace Tests;

use DiDom\Document;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Message;
use Hexlet\Code\Controller\IndexController;
use Hexlet\Code\Url;
use Hexlet\Code\UrlCheck;
use Hexlet\Code\UrlCheckRepository;
use Hexlet\Code\UrlRepository;
use Hexlet\Code\UrlValidator;
use PDO;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Slim\Flash\Messages;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;

class IndexControllerTest extends TestCase
{
    private IndexController $controller;

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

        $controller = new IndexController(
            $this->createMock(PDO::class),
            $view,
            $this->createMock(Messages::class),
            $this->createMock(UrlRepository::class),
            $this->createMock(Url::class),
            $this->createMock(UrlValidator::class),
            $this->createMock(UrlCheck::class),
            $this->createMock(UrlCheckRepository::class),
            $this->createMock(RouteParserInterface::class),
            $this->createMock(Client::class),
            $this->createMock(Document::class)
        );

        $controller->indexAction($request, $response);
    }
}
