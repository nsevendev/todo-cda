<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Entity\User\Dto;

use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\User\Dto\UserCreateDto;
use Tocda\Entity\User\ValueObject\UserEmail;
use Tocda\Entity\User\ValueObject\UserPassword;
use Tocda\Entity\User\ValueObject\UserUsername;
use Tocda\Tests\Faker\Dto\User\UserCreateDtoFaker;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[CoversClass(UserCreateDto::class), CoversClass(UserEmail::class), CoversClass(UserUsername::class), CoversClass(UserPassword::class)]
class UserEntityCreateDtoTest extends TocdaUnitTestCase
{
    public function testUserEntityCreateDto(): void
    {
        $userEntityCreateDto = UserCreateDtoFaker::new();

        self::assertNotNull($userEntityCreateDto);

        self::assertInstanceOf(UserCreateDto::class, $userEntityCreateDto);

        self::assertSame('paquito', $userEntityCreateDto->username);
        self::assertSame('paquito@gmail.com', $userEntityCreateDto->email);
        self::assertSame('Paquito123?', $userEntityCreateDto->password);

        self::assertSame('paquito', (string) $userEntityCreateDto->username);
        self::assertSame('paquito@gmail.com', (string) $userEntityCreateDto->email);
        self::assertSame('Paquito123?', (string) $userEntityCreateDto->password);
    }

    public function testUserEntityCreateDtoWithFunctionNew(): void
    {
        $userEntityCreateDto = UserCreateDto::new(
            'paquito',
            'paquito@gmail.com',
            'Paquito123?'
        );

        self::assertNotNull($userEntityCreateDto);

        self::assertInstanceOf(UserCreateDto::class, $userEntityCreateDto);

        self::assertSame('paquito', $userEntityCreateDto->username);
        self::assertSame('paquito@gmail.com', $userEntityCreateDto->email);
        self::assertSame('Paquito123?', $userEntityCreateDto->password);

        self::assertSame('paquito', (string) $userEntityCreateDto->username);
        self::assertSame('paquito@gmail.com', (string) $userEntityCreateDto->email);
        self::assertSame('Paquito123?', (string) $userEntityCreateDto->password);
    }
}
