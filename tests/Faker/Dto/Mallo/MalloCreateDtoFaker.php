<?php

declare(strict_types=1);

namespace Tocda\Tests\Faker\Dto\Mallo;

use Tocda\Entity\Mallo\Dto\MalloCreateDto;

class MalloCreateDtoFaker
{
    public static function new(): MalloCreateDto // fonction new() est une méthode publique et statique qui crée et retourne un objet de type MalloCreateDto.
    {
        return new MalloCreateDto(
            'John',
            'Doe',
            13
        );
    }
}
