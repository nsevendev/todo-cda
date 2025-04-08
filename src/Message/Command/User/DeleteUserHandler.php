<?php

declare(strict_types=1);

namespace Tocda\Message\Command\User;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Tocda\Entity\User\Dto\UserPublishDeletedDto;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\User\UserBadRequestException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\Mercure\MercurePublish;
use Tocda\Repository\User\UserRepository;

#[AsMessageHandler(bus: 'command.bus')]
class DeleteUserHandler
{
    public function __construct(private readonly UserRepository $userRepository, private readonly MercurePublish $mercurePublish) {}

    /**
     * @return void|null
     *
     * @throws MercureInvalidArgumentException
     * @throws UserBadRequestException
     */
    public function __invoke(DeleteUserCommand $command)
    {
        $user = $this->userRepository->find($command->id);

        if (null === $user) {
            throw new UserBadRequestException(errors: [Error::create('user', "Aucun utilisateur n'a été trouvé")]);
        }

        $userDto = UserPublishDeletedDto::fromArray($user);

        $this->userRepository->remove($user);

        $this->mercurePublish->publish(
            topic: '/user-deleted',
            data: $userDto->toArray()
        );
    }
}
