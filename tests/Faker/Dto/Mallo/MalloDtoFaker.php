<?php

declare(strict_types=1);

namespace Tocda\Tests\Faker\Dto\Mallo;

use Symfony\Component\Uid\Uuid;
use Tocda\Entity\Mallo\Dto\MalloDto;

class MalloDtoFaker // Nouvelle class
{
    public static function new(): MalloDto // Fonction statique qui retourne un objet de type MalloDto
    {
        return new MalloDto(
            id: Uuid::v7()->toString(),
            firstname: 'Mallo',
            lastname: 'Zimmermann',
            number: 67,
            createdAt: '2000-03-31 12:00:00',
            updatedAt: '2000-03-31 12:00:00',
        );
    }
}
