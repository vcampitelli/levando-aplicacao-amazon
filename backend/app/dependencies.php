<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Domain\User\User;
use Cycle\Database;
use Cycle\ORM;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        Database\DatabaseManager::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            return new Database\DatabaseManager(
                new Database\Config\DatabaseConfig([
                    'default' => 'default',
                    'databases' => [
                        'default' => ['connection' => 'mysql']
                    ],
                    'connections' => [
                        'mysql' => new Database\Config\MySQLDriverConfig(
                            connection: new Database\Config\MySQL\DsnConnectionConfig(
                                dsn: $settings->get('pdo_dsn'),
                                user: $settings->get('pdo_username'),
                                password: $settings->get('pdo_password'),
                            ),
                            queryCache: true,
                        ),
                    ],
                ])
            );
        },
        ORM\EntityManagerInterface::class => function (ContainerInterface $c) {
            return new ORM\EntityManager($c->get(ORM\ORM::class));
        },
        ORM\ORM::class => function (ContainerInterface $c) {
            return new ORM\ORM(
                new ORM\Factory($c->get(Database\DatabaseManager::class)),
                new ORM\Schema([
                    'user' => [
                        ORM\SchemaInterface::MAPPER => ORM\Mapper\Mapper::class, // default POPO mapper
                        ORM\SchemaInterface::ENTITY => User::class,
                        ORM\SchemaInterface::DATABASE => 'default',
                        ORM\SchemaInterface::TABLE => 'users',
                        ORM\SchemaInterface::PRIMARY_KEY => 'id',
                        ORM\SchemaInterface::COLUMNS => [
                            'id' => 'id',  // property => column
                            'username' => 'username',
                            'name' => 'name',
                        ],
                        ORM\SchemaInterface::TYPECAST => [
                            'id' => 'int',
                        ],
                        ORM\SchemaInterface::RELATIONS => [],
                    ]
                ])
            );
        },
    ]);
};
