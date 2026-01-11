<?php

declare(strict_types=1);

use App\Controller\ExampleController;
use Slim\App;

return function (App $app): void {
    $app->get('/', [ExampleController::class, 'index'])->setName('index');
};
