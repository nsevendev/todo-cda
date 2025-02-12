<?php

declare(strict_types=1);

namespace Tocda\Tests\Faker\Dto\Ping;

use Tocda\Entity\Ping\Dto\PingCreateDto;
use Tocda\Entity\Ping\ValueObject\PingMessage;
use Tocda\Entity\Ping\ValueObject\PingStatus;

class PingCreateDtoFaker
{
    public static function new(): PingCreateDto
    {
        return new PingCreateDto(
            PingStatus::fromValue(200),
            PingMessage::fromValue('Le ping à réussi en faker')
        );
    }
}
