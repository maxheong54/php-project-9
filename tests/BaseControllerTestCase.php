<?php

namespace Tests;

use DiDom\Document;
use GuzzleHttp\Client;
use Hexlet\Code\Controller\BaseController;
use Hexlet\Code\Controller\IndexController;
use Hexlet\Code\Controller\UrlCheckController;
use Hexlet\Code\Controller\UrlController;
use Hexlet\Code\Url;
use Hexlet\Code\UrlCheck;
use Hexlet\Code\UrlCheckRepository;
use Hexlet\Code\UrlRepository;
use Hexlet\Code\UrlValidator;
use PDO;
use PHPUnit\Framework\TestCase;
use Slim\Flash\Messages;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;

class BaseControllerTestCase extends TestCase
{
    public function getController(
        string $controllerName,
        array $params = []
    ): BaseController|IndexController|UrlCheckController|UrlController {
        $controllerName = "Hexlet\Code\Controller\\" . $controllerName;

        return new $controllerName(
            $params['pdo'] ?? $this->createMock(PDO::class),
            $params['view'] ?? $this->createMock(Twig::class),
            $params['flash'] ?? $this->createMock(Messages::class),
            $params['urlRepository'] ?? $this->createMock(UrlRepository::class),
            $params['url'] ?? $this->createMock(Url::class),
            $params['UrlValidator'] ?? $this->createMock(UrlValidator::class),
            $params['urlCheck'] ?? $this->createMock(UrlCheck::class),
            $params['urlCheckRepository'] ?? $this->createMock(UrlCheckRepository::class),
            $params['router'] ?? $this->createMock(RouteParserInterface::class),
            $params['httpClient'] ?? $this->createMock(Client::class),
            $params['dom'] ?? $this->createMock(Document::class)
        );
    }
}
