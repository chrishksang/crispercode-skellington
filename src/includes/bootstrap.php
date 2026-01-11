<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/constants.php';

use CrisperCode\Cache\Cache;
use CrisperCode\Cache\CacheBackendInterface;
use CrisperCode\Cache\DatabaseCacheBackend;
use CrisperCode\Config\FrameworkConfig;
use CrisperCode\Database\DatabaseFactory;
use CrisperCode\EntityFactory;
use CrisperCode\Twig\TwigFactory;
use CrisperCode\Utils\LoggerFactory;
use CrisperCode\Utils\PerformanceMonitor;
use DI\Container;
use DI\ContainerBuilder;
use Monolog\Logger;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\Twig;

use function DI\factory;
use function DI\get;

return function(): Container
{
    // Disable error reporting in non-development environments
    if (getenv('ENVIRONMENT') !== 'development') {
        error_reporting(0);
    }

    $containerBuilder = new ContainerBuilder();
    $containerBuilder->addDefinitions([
        // Framework configuration - central place for all paths and settings
        FrameworkConfig::class => factory(function (): FrameworkConfig {
            return FrameworkConfig::fromEnvironment(
                __DIR__ . '/../',
                'myapp'
            );
        }),

        // Register ResponseFactory for middleware and other services needing PSR-17 factories
        ResponseFactoryInterface::class => factory(function (): ResponseFactoryInterface {
            return new ResponseFactory();
        }),

        // PerformanceMonitor - start() called per-request in index.php
        PerformanceMonitor::class => factory(function (): PerformanceMonitor {
            return new PerformanceMonitor();
        }),

        // Lazy instantiations using FrameworkConfig
        Twig::class => factory(function (FrameworkConfig $config): Twig {
            return TwigFactory::create($config);
        }),

        MeekroDB::class => factory(function (FrameworkConfig $config): MeekroDB {
            return DatabaseFactory::create($config);
        }),

        Logger::class => factory(function (FrameworkConfig $config): Logger {
            return LoggerFactory::create($config);
        }),

        // LoggerInterface points to the same Logger instance
        LoggerInterface::class => get(Logger::class),

        // Lazy instantiation of EntityFactory
        EntityFactory::class => factory(function (MeekroDB $db): EntityFactory {
            return new EntityFactory($db);
        }),

        // Cache backend - database-backed implementation
        CacheBackendInterface::class => factory(function (MeekroDB $db): CacheBackendInterface {
            return new DatabaseCacheBackend($db);
        }),

        // Cache service with pluggable backend
        Cache::class => factory(function (CacheBackendInterface $backend): Cache {
            return new Cache($backend);
        }),

        // Add your application-specific services here
    ]);

    return $containerBuilder->build();
};
