<?php

declare(strict_types=1);

namespace Tocda\Tests\Faker\Dto\User;

use Tocda\Entity\User\Dto\UserCreateDto;

class UserCreateDtoFaker
{
    public static function new(): UserCreateDto
    {
        return new UserCreateDto(
            'paquito',
            'paquito@gmail.com',
            'Paquito123?'
        );
    }
}
