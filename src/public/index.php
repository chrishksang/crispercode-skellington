<?php

declare(strict_types=1);

use CrisperCode\Config\FrameworkConfig;
use CrisperCode\Middleware\ErrorMiddleware;
use CrisperCode\Utils\PerformanceMonitor;
use CrisperCode\Utils\SessionConfig;
use DI\Bridge\Slim\Bridge;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

$container = (require __DIR__ . '/../includes/bootstrap.php')();

// Configure session settings once (ini settings persist across requests)
SessionConfig::configure($container->get(FrameworkConfig::class));

$app = Bridge::create($container);

// Global middleware stack
$app->add(TwigMiddleware::create($app, $container->get(Twig::class)));
$app->add(ErrorMiddleware::class);

// Load route definitions
(require __DIR__ . '/../routes/example.php')($app);

// FrankenPHP worker mode: handle multiple requests in a single process
if (function_exists('frankenphp_handle_request')) {
    $performanceMonitor = $container->get(PerformanceMonitor::class);

    $requestHandler = static function () use ($app, $performanceMonitor): void {
        $performanceMonitor->start();
        session_start();

        try {
            $app->run();
        } finally {
            session_write_close();
        }
    };

    // Loop until FrankenPHP signals shutdown
    while (frankenphp_handle_request($requestHandler)) {
        // Superglobals ($_GET, $_POST, $_SERVER, etc.) are reset by FrankenPHP
    }
} else {
    // Traditional PHP-FPM / CGI mode
    $container->get(PerformanceMonitor::class)->start();
    session_start();
    $app->run();
}
