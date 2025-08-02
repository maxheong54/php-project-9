<?php

namespace Hexlet\Code\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class IndexController extends BaseController
{
    public function indexAction(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $currentPath = $request->getUri()->getPath();
        $params = ['currentPath' => $currentPath];
        return $this->view->render($response, 'main.html.twig', $params);
    }
}
