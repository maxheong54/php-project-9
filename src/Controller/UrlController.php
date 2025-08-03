<?php

namespace Hexlet\Code\Controller;

use Hexlet\Code\Url;
use Hexlet\Code\UrlCheckRepository;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UrlController extends BaseController
{
    public function indexAction(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $urls = $this->urlRepository->getEntities();
        $currentPath = $request->getUri()->getPath();
        $checks = $this->urlCheckRepository;

        $params = [
            'urls' => $urls,
            'currentPath' => $currentPath,
            'checks' => $checks
        ];

        return $this->view->render($response, 'urls.html.twig', $params);
    }

    public function createAction(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $data = (array) $request->getParsedBody();

        $validator = $this->urlValidator;
        $errors = $validator->validateUrl($data);

        $urlName = $data['url']['name'];
        $url = Url::create($urlName);

        if (count($errors) === 0) {
            if ($this->urlRepository->exists($url)) {
                $flashMessage = 'Страница уже существует';
            } else {
                $flashMessage = 'Страница успешно добавлена';
                $this->urlRepository->save($url);
            }

            $this->flash->addMessage('success', $flashMessage);
            return $response->withHeader(
                'Location',
                $this->router->urlFor('urls.show', ['id' => (string) $url->getId()])
            )->withStatus(302);
        }

        $currentPath = $request->getUri()->getPath();

        $params = [
            'url' => $url,
            'errors' => $errors,
            'currentPath' => $currentPath
        ];

        return $this->view->render($response, 'main.html.twig', $params);
    }

    public function getUrlAction(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $id = (int) $args['id'];

        $url = $this->urlRepository->find($id);

        if ($url === null) {
            $response->getBody()->write('Page not found');
            return $response->withStatus(404);
        }

        $messages = (array) $this->flash->getMessages()['success'];
        $flash = Arr::last($messages);
        $currentPath = $request->getUri()->getPath();
        $checks = $this->urlCheckRepository->getChecks($id);

        $params = [
            'flash' => $flash,
            'url' => $url,
            'currentPath' => $currentPath,
            'checks' => $checks
        ];

        return $this->view->render($response, 'url.html.twig', $params);
    }
}
