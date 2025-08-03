<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DI\Container;
use Dotenv\Dotenv;
use Hexlet\Code\Connection;
use Hexlet\Code\Routes;
use Hexlet\Code\TableCreator;
use Hexlet\Code\UrlValidator;
use Slim\Factory\AppFactory;
use Slim\Flash\Messages;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();
$dotenv->required('DATABASE_URL')->notEmpty();

$conn = Connection::create($_ENV['DATABASE_URL']);
(new TableCreator())->run($conn);

session_start();

$container = new Container();

$container->set(Twig::class, fn () => Twig::create(__DIR__ . '/../templates'));
$container->set(PDO::class, fn() => $conn);
$container->set(Messages::class, fn () => new Messages());

$app = AppFactory::createFromContainer($container);

$app->add(TwigMiddleware::create($app, $container->get(Twig::class)));
$app->addErrorMiddleware(true, true, true);

$container->set(
    RouteParserInterface::class,
    fn() => $app->getRouteCollector()->getRouteParser()
);

Routes::init($app);

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$app->run();
