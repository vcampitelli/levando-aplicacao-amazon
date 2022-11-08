<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use Cycle\ORM\ORM;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
{

    public function __construct(
        LoggerInterface $logger,
        protected readonly UserRepositoryInterface $userRepository
    ) {
        parent::__construct($logger);
    }
}
