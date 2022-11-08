<?php

declare(strict_types=1);

use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use Cycle\ORM\ORM;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepositoryInterface::class => function (ContainerInterface $c) {
            /** @var Cycle\ORM\Select\Repository $repository */
            $repository = new \App\Infrastructure\Persistence\User\PdoUserRepository(
                repository: $c->get(ORM::class)->getRepository(User::class),
                entityManager: $c->get(\Cycle\ORM\EntityManagerInterface::class),
            );
            return $repository;
        }
    ]);
};
