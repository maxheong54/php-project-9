<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DI\Container;
use Dotenv\Dotenv;
use Hexlet\Code\Connection;
use Hexlet\Code\HttpErrorHandler;
use Hexlet\Code\Routes;
use Hexlet\Code\TableCreator;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
ini_set('display_errors', '0');

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();
$dotenv->required('DATABASE_URL')->notEmpty();

$conn = Connection::create($_ENV['DATABASE_URL']);
(new TableCreator())->run($conn);

session_start();

$container = new Container();

$container->set(Twig::class, fn () => Twig::create(__DIR__ . '/../templates'));
$container->set(PDO::class, fn() => $conn);

$app = AppFactory::createFromContainer($container);

$callableResolver = $app->getCallableResolver();
$responseFactory = $app->getResponseFactory();

$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory, $container->get(Twig::class));

$app->addRoutingMiddleware();

$app->add(TwigMiddleware::create($app, $container->get(Twig::class)));
$errorMiddleware = $app->addErrorMiddleware(false, true, true);


$errorMiddleware->setDefaultErrorHandler($errorHandler);

$container->set(
    RouteParserInterface::class,
    fn() => $app->getRouteCollector()->getRouteParser()
);

Routes::init($app);

$app->run();
