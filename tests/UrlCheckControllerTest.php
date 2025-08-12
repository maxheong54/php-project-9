<?php

namespace Tests;

use GuzzleHttp\Client;
use Hexlet\Code\Url;
use Hexlet\Code\UrlRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Flash\Messages;

class UrlCheckControllerTest extends BaseControllerTestCase
{
    public function testCheckUrl(): void
    {
        $url = $this->createConfiguredMock(
            Url::class,
            ['getName' => 'example.com']
        );

        $urlRepository = $this->createConfiguredMock(
            UrlRepository::class,
            ['find' => $url]
        );

        $httpClient = $this->createMock(Client::class);
        $httpResponse = $this->createMock(ResponseInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                'example.com',
                [
                    'connect_timeout' => 2.0,
                    'timeout' => 3.0
                ]
            )->willReturn($httpResponse);

        $flash = $this->createMock(Messages::class);
        $flash->expects($this->once())
            ->method('addMessage');

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $response->expects($this->once())
            ->method('withHeader')
            ->with(
                'Location',
                ''
            )->willReturnSelf();

        $response->expects($this->once())
            ->method('withStatus')
            ->with(303);

        $controller = $this->getController(
            'UrlCheckController',
            [
                'url' => $url,
                'httpClient' => $httpClient,
                'urlRepository' => $urlRepository,
                'flash' => $flash
            ]
        );

        $controller->checkUrl($request, $response, []);
    }
}
