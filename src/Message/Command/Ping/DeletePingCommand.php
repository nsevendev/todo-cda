<?php

declare(strict_types=1);

namespace Tocda\Message\Command\Ping;

class DeletePingCommand
{
    public function __construct(
        public string $id,
    ) {}
}
