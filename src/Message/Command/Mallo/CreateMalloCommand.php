<?php

declare(strict_types=1);

namespace Tocda\Message\Command\Mallo;

use Tocda\Entity\Mallo\Dto\MalloCreateDto;

class CreateMalloCommand
{
    public function __construct(
        public MalloCreateDto $malloEntityCreateDto,
    ) {}
}
