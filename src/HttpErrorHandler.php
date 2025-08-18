<?php

namespace Hexlet\Code;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpException;
use Slim\Handlers\ErrorHandler;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Views\Twig;

class HttpErrorHandler extends ErrorHandler
{
    public function __construct(
        CallableResolverInterface $callableResolver,
        ResponseFactoryInterface $responseFactory,
        private Twig $view
    ) {
        parent::__construct($callableResolver, $responseFactory);
    }

    protected function respond(): ResponseInterface
    {
        $exception = $this->exception;

        $statusCode = $exception instanceof HttpException ? $exception->getCode() : 500;
        $tamplate = $statusCode === 404 ? '404.html.twig' : '500.html.twig';

        $response = $this->responseFactory->createResponse($statusCode);
        return $this->view->render($response, $tamplate);
    }
}
