<?php

declare(strict_types=1);

namespace Tocda\Tests\Faker\Dto\User;

use Symfony\Component\Uid\Uuid;
use Tocda\Entity\User\Dto\UserDto; 

class UserDtoFaker 
{
    public static function new(): UserDto
    {
        return new UserDto(
            id: Uuid::v7()->toString(),
            username: 'paquito',
            email: 'paquito@gmail.com',
            password: 'paquito123',
            createdAt: '2004-12-10 12:00:00',
            updatedAt: '2004-12-10 12:00:00',

        );
    }
}