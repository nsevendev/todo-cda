<?php

declare(strict_types=1);

namespace Tocda\Tests\Faker\Entity\Mallo;

use Tocda\Entity\Mallo\Mallo;

final class MalloFaker
{
    public static function new(): Mallo
    {
        return new Mallo(
            firstname: 'Mallo',
            lastname: 'Zimmermann',
            number: 67,
        );
    }
}
