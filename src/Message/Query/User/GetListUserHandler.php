<?php

declare(strict_types=1);

namespace Tocda\Message\Query\User;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Tocda\Entity\User\Dto\UserDto;
use Tocda\Entity\User\User;
use Tocda\Repository\User\UserRepository;

#[AsMessageHandler]
class GetListUserHandler
{
    public function __construct(private UserRepository $userRepository) {}

    /**
     * @return UserDto[]
     */
    public function __invoke(GetListUserQuery $query): array // pq
    {
        /** @var User[] $listUser */
        $listUser = $this->userRepository->findAll();

        return UserDto::toListUser($listUser);
    }
}
