<?php

declare(strict_types=1);

namespace App\Domain\User;

interface UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findById(int $id): User;

    /**
     * @param string $username
     * @return User
     * @throws UserNotFoundException
     */
    public function findByUsername(string $username): User;

    /**
     * @param User $user
     * @return User
     */
    public function save(User $user): User;

    /**
     * @param User $user
     * @return UserRepositoryInterface
     */
    public function delete(User $user): UserRepositoryInterface;
}
