<?php

namespace Hexlet\Code\Controller;

use Hexlet\Code\Url;
use Hexlet\Code\UrlRepository;
use Hexlet\Code\UrlValidator;
use PDO;
use Psr\Container\ContainerInterface;
use Slim\Flash\Messages;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;

class BaseController
{
    public function __construct(
        protected ContainerInterface $container,
        protected PDO $pdo,
        protected Twig $view,
        protected Messages $flash,
        protected UrlRepository $urlRepository,
        protected Url $url,
        protected UrlValidator $urlValidator,
        protected RouteParserInterface $router
    ) {
    }
}
