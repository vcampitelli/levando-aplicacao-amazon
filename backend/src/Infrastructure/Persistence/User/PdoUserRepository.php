<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepositoryInterface;
use Cycle\ORM\EntityManagerInterface;
use Cycle\ORM\RepositoryInterface;
use Cycle\ORM\Select\Repository;

class PdoUserRepository implements UserRepositoryInterface
{

    public function __construct(
        private readonly RepositoryInterface $repository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param User $user
     * @return User
     */
    public function save(User $user): User
    {
        $this->entityManager->persist($user);
        $this->entityManager->run();
        return $user;
    }

    /**
     * @return array|User[]
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findById(int $id): User
    {
        $user = $this->repository->findByPK($id);
        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @param string $username
     * @return User
     * @throws UserNotFoundException
     */
    public function findByUsername(string $username): User
    {
        $user = $this->repository->findOne([
            'username' => $username,
        ]);
        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function delete(User $user): UserRepositoryInterface
    {
        $this->entityManager->delete($user);
        $this->entityManager->run();
        return $this;
    }
}
