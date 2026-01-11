<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class ExampleController
{
    public function __construct(
        private Twig $twig
    ) {
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render($response, 'index.twig', [
            'title' => 'Welcome',
            'message' => 'Your application is running.',
        ]);
    }
}
