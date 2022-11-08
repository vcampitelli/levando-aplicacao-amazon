<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;

class CreateUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = (array) $this->getFormData();
        $data['username'] = trim($data['username'] ?? '');
        if (empty($data['username'])) {
            throw new HttpBadRequestException($this->request, 'Campo "username" é obrigatório');
        }
        $data['name'] = trim($data['name'] ?? '');
        if (empty($data['name'])) {
            throw new HttpBadRequestException($this->request, 'Campo "name" é obrigatório');
        }

        try {
            $this->userRepository->findByUsername($data['username']);
            throw new HttpException(
                $this->request,
                'Já existe um usuário com o nome "' . $data['username'] . '"',
                StatusCodeInterface::STATUS_CONFLICT,
            );
        } catch (UserNotFoundException) {
            $user = $this->userRepository->save(
                new User(null, $data['username'], $data['name'])
            );
            $userId = $user->getId();

            $this->logger->info("User of id `${userId}` was created.");

            return $this->respondWithData($user);
        }
    }
}
