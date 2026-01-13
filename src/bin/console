#!/usr/bin/env php
<?php

declare(strict_types=1);

use CrisperCode\Console\Application;
use CrisperCode\Console\Command\WorkCommand;
use CrisperCode\Console\EntityProvider\FrameworkEntityProvider;
use Psr\Log\LoggerInterface;

$logger = null;

try {
    // Build the DI container to get dependencies
    $container = (require __DIR__ . '/../includes/bootstrap.php')();

    /** @var \MeekroDB $db */
    $db = $container->get(\MeekroDB::class);

    /** @var LoggerInterface $logger */
    $logger = $container->get(LoggerInterface::class);

    // Create console application with framework entity provider
    $app = Application::create($db, [new FrameworkEntityProvider()], $logger);

    // Register queue:work command
    $app->addCommand($container->get(WorkCommand::class));

    exit($app->run());
} catch (\Throwable $e) {
    // Log error if logger is available
    if ($logger !== null) {
        $logger->error('Console error', [
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }

    // Output to stderr based on environment
    if (getenv('ENVIRONMENT') === 'development') {
        fwrite(STDERR, "Error: " . $e->getMessage() . "\n");
        fwrite(STDERR, $e->getTraceAsString() . "\n");
    } else {
        fwrite(STDERR, "An error occurred. Check logs for details.\n");
    }
    exit(1);
}
