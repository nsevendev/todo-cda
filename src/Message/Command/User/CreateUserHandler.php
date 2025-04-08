<?php

declare(strict_types=1);

namespace Tocda\Message\Command\User;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Tocda\Entity\User\Dto\UserDto;
use Tocda\Entity\User\User;
use Tocda\Entity\User\ValueObject\UserEmail;
use Tocda\Entity\User\ValueObject\UserPassword;
use Tocda\Entity\User\ValueObject\UserUsername;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\User\UserInvalidArgumentException;
use Tocda\Infrastructure\Mercure\MercurePublish;
use Tocda\Repository\User\UserRepository;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreateUserHandler
{
    public function __construct(
        private UserRepository $userEntityRepository,
        private MercurePublish $mercurePublish,
    ) {}

    /**
     * @throws MercureInvalidArgumentException
     * @throws UserInvalidArgumentException
     */
    public function __invoke(CreateUserCommand $command): void
    {
        $user = new User(
            username: UserUsername::fromValue($command->userEntityCreateDto->username),
            email: UserEmail::fromValue($command->userEntityCreateDto->email),
            password: UserPassword::fromValue($command->userEntityCreateDto->password)
        );

        $this->userEntityRepository->save( // Appel de la méthode save de la classe UserRepository
            user: $user
        );

        $userDto = UserDto::fromArray($user);

        $this->mercurePublish->publish(
            topic: '/user-created',
            data: $userDto->toArray()
        );
    }
}
