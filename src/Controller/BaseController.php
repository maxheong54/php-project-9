<?php

namespace Hexlet\Code\Controller;

use DiDom\Document;
use GuzzleHttp\Client;
use Hexlet\Code\Url;
use Hexlet\Code\UrlCheck;
use Hexlet\Code\UrlCheckRepository;
use Hexlet\Code\UrlRepository;
use Hexlet\Code\UrlValidator;
use PDO;
use Slim\Flash\Messages;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;

class BaseController
{
    public function __construct(
        // protected ContainerInterface $container,
        protected PDO $pdo,
        protected Twig $view,
        protected Messages $flash,
        protected UrlRepository $urlRepository,
        protected Url $url,
        protected UrlValidator $urlValidator,
        protected UrlCheck $urlCheck,
        protected UrlCheckRepository $urlCheckRepository,
        protected RouteParserInterface $router,
        protected Client $httpClient,
        protected Document $dom
    ) {
    }
}
