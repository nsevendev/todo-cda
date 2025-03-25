<?php

declare(strict_types=1);

namespace Tocda\Message\Command\Xavier;

use Tocda\Entity\Xavier\Dto\XavierCreateDto;

class CreateXavierCommand
{
    public function __construct(
        public XavierCreateDto $xavierEntityCreateDto,
    ) {}
}
