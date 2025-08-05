<?php

namespace Hexlet\Code\Controller;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\TransferException;
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
        $urlName = $url?->getName() ?? '';
        $urlCheck = UrlCheck::fromArray(['url_id' => $urlId]);

        try {
            $httpResponse = $this->httpClient->request(
                'GET',
                $urlName,
                [
                    'connect_timeout' => 2.0,
                    'timeout' => 3.0
                ]
            );
            $statusCode = $httpResponse->getStatusCode();
            $urlCheck->setStatusCode($statusCode);
            $this->urlCheckRepository->save($urlCheck);
            $this->flash->addMessage('success', 'Проверка успешно выполнена');
        } catch (ConnectException $e) {
            $this->flash->addMessage('error', 'Ошибка сети: проверка не выполнена');
        } catch (TransferException $e) {
            $this->flash->addMessage('error', 'Ошибка при выполнении запроса');
        }

        return $response->withHeader(
            'Location',
            $this->router->urlFor('urls.show', ['id' => $args['id']])
        )->withStatus(302);
    }
}
