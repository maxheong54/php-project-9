<?php

namespace Hexlet\Code\Controller;

use Hexlet\Code\Controller\BaseController;
use Hexlet\Code\UrlCheck;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UrlCheckController extends BaseController
{
    public function checkUrl(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $urlId = (int) $args['id'];
        $url = $this->urlRepository->find($urlId);
        $urlCheck = UrlCheck::create($urlId);
        $this->urlCheckRepository->save($urlCheck);

        return $response->withHeader(
            'Location',
            $this->router->urlFor('urls.show', ['id' => $args['id']])
        )->withStatus(302);
    }
}
