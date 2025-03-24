<?php

declare(strict_types=1);

namespace Tocda\Tests\Faker\Entity\Xavier;

use Tocda\Entity\Xavier\Xavier;

final class XavierFaker
{
    public static function new(): Xavier
    {
        return new Xavier(
            firstname: 'John',
            lastname: 'Doe',
            number: 0,
        );
    }
}
