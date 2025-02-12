<?php

declare(strict_types=1);

namespace Tocda\Tests\Faker\Entity\Ping;

use Tocda\Entity\Ping\Ping;

final class PingFaker
{
    public static function new(): Ping
    {
        return new Ping(
            status: 200,
            message: 'Le ping à réussi'
        );
    }
}
