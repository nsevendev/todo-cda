<?php

declare(strict_types=1);

namespace Tocda\Message\Command\Mallo;

class DeleteMalloCommand
{
    public function __construct(
        public string $id,
    ) {}
}
