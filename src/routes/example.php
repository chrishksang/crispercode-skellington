<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Views\Twig;

return function (App $app): void {
    // Example route using Twig template
    $app->get('/', function (ServerRequestInterface $request, ResponseInterface $response, Twig $twig) {
        return $twig->render($response, 'index.twig', [
            'title' => 'Welcome to CrisperCode Skeleton!',
            'message' => 'Your application is up and running.'
        ]);
    })->setName('index');
};
