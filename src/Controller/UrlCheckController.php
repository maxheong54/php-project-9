<?php

namespace Hexlet\Code\Controller;

use DiDom\Element;
use GuzzleHttp\Exception\ClientException;
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

        try {
            $httpResponse = $this->httpClient->request(
                'GET',
                $urlName,
                [
                    'connect_timeout' => 2.0,
                    'timeout' => 3.0
                ]
            );

            $html = $httpResponse->getBody()->getContents();
            $statusCode = $httpResponse->getStatusCode();
            $this->createUrlCheckFromHtml($html, $statusCode, $urlId);

            $this->flash->addMessage('success', 'Страница успешно проверена');
        } catch (ClientException $e) {
            $html = $e->getResponse()->getBody()->getContents();
            $statusCode = $e->getCode();
            $this->createUrlCheckFromHtml($html, $statusCode, $urlId);

            $this->flash->addMessage('warning', 'Проверка была выполнена успешно, но сервер ответил с ошибкой');
        } catch (ConnectException $e) {
            if (str_contains($e->getMessage(), 'cURL error 6')) {
                $this->flash->addMessage('error', 'Произошла ошибка при проверке, не удалось подключиться');
            } else {
                $this->flash->addMessage('error', 'Ошибка сети: проверка не выполнена');
            }
        } catch (TransferException $e) {
            $this->flash->addMessage('error', 'Ошибка при выполнении запроса');
        }

        return $response->withHeader(
            'Location',
            $this->router->urlFor('urls.show', ['id' => $args['id']])
        )->withStatus(303);
    }

    private function createUrlCheckFromHtml(string $html, int $statusCode, int $urlId): void
    {
        $this->dom->loadHtml($html);

        $elem = $this->dom->first('h1');
        $h1 = $elem instanceof Element ? $elem->text() : null;
        $elem = $this->dom->first('title');
        $title = $elem instanceof Element ? $elem->text() : null;
        $elem = $this->dom->first('meta[name=description]');
        $description = $elem instanceof Element ? $elem->attr('content') : null;

        $urlCheck = UrlCheck::fromArray([
            'url_id' => $urlId,
            'status_code' => $statusCode,
            'h1' => $h1,
            'title' => $title,
            'description' => $description
        ]);

        $this->urlCheckRepository->save($urlCheck);
    }
}
