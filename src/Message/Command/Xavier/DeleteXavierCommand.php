<?php

declare(strict_types=1);

namespace Tocda\Message\Command\Xavier;

class DeleteXavierCommand
{
    public function __construct(
        public string $id,
    ) {}
}
