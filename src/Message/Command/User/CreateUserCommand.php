<?php

declare(strict_types=1);

namespace Tocda\Message\Command\User;

use Tocda\Entity\User\Dto\UserCreateDto;

class CreateUserCommand
{
    public function __construct(
        public UserCreateDto $userEntityCreateDto,
    ) {}
}
