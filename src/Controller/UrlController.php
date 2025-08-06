<?php

namespace Hexlet\Code\Controller;

use Hexlet\Code\Url;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UrlController extends BaseController
{
    public function indexAction(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $urls = $this->urlRepository->getUrls();
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

        $errors = $this->urlValidator->validateUrl($data);

        $urlNameParts = parse_url($data['url']['name']);
        $scheme = $urlNameParts['scheme'] ?? '';
        $host = $urlNameParts['host'] ?? '';
        $urlName = "{$scheme}://{$host}";
        $url = Url::fromArray(['name' => $urlName]);

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
            )->withStatus(303);
        }

        $currentPath = $request->getUri()->getPath();

        $url->setName($data['url']['name']);
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
            // $response->getBody()->write('Page not found');
            // return $response->withStatus(404);
            return $this->view->render(
                $response->withStatus(404),
                '404.html.twig'
            );
        }

        $messages = (array) $this->flash->getMessages();
        $currentPath = $request->getUri()->getPath();
        $checks = $this->urlCheckRepository->getChecks($id);

        $params = [
            'flash' => $messages,
            'url' => $url,
            'currentPath' => $currentPath,
            'checks' => $checks
        ];

        return $this->view->render($response, 'url.html.twig', $params);
    }
}
