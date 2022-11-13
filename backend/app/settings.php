<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'            => false,
                'logErrorDetails'     => false,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'pdo_dsn' => $_ENV['APP_PDO_DSN'] ?? 'mysql:host=localhost;dbname=app',
                'pdo_username' => $_ENV['APP_PDO_USERNAME'] ?? 'root',
                'pdo_password' => $_ENV['APP_PDO_PASSWORD'] ?? 'root',
            ]);
        }
    ]);
};
