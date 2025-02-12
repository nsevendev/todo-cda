<?php

declare(strict_types=1);

namespace Tocda\Message\Command\Ping;

use Tocda\Entity\Ping\Dto\PingCreateDto;

class CreatePingCommand
{
    public function __construct(
        public PingCreateDto $pingEntityCreateDto,
    ) {}
}
