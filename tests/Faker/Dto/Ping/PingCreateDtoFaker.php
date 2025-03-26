<?php

declare(strict_types=1);

namespace Tocda\Tests\Faker\Dto\Ping;

use Tocda\Entity\Ping\Dto\PingCreateDto;

class PingCreateDtoFaker
{
    public static function new(): PingCreateDto
    {
        return new PingCreateDto(
            200,
            'Le ping à réussi en faker'
        );
    }
}
