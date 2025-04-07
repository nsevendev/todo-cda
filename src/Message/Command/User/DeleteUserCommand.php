<?php

declare(strict_types=1);

namespace Tocda\Message\Command\User;

class DeleteUserCommand
{
    public function __construct(
        public string $id,
    ) {}
}
