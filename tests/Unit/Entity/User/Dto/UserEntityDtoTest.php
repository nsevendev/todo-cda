<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Entity\User\Dto;

use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\User\Dto\UserDto;
use Tocda\Entity\User\User;
use Tocda\Entity\User\ValueObject\UserEmail;
use Tocda\Entity\User\ValueObject\UserPassword;
use Tocda\Entity\User\ValueObject\UserUsername;
use Tocda\Tests\Faker\Dto\User\UserDtoFaker;
use Tocda\Tests\Faker\Entity\User\UserFaker;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[CoversClass(User::class), CoversClass(UserDto::class), CoversClass(UserEmail::class), CoversClass(UserUsername::class), CoversClass(UserPassword::class)]
class UserEntityDtoTest extends TocdaUnitTestCase
{
    public function testUserDtoFromArray(): void
    {
        $userEntity = UserFaker::new();

        self::assertInstanceOf(User::class, $userEntity);

        self::assertSame('paquito', $userEntity->username()->value());
        self::assertSame('paquito@gmail.com', $userEntity->email()->value());
        self::assertSame('Paquito123?', $userEntity->password()->value());

        self::assertNotNull($userEntity);
        self::assertInstanceOf(User::class, $userEntity);

        $userDto = UserDto::fromArray($userEntity);

        self::assertNotNull($userDto);
        self::assertInstanceOf(UserDto::class, $userDto);

        self::assertSame('paquito', $userDto->username);
        self::assertSame('paquito@gmail.com', $userDto->email);
        self::assertSame('Paquito123?', $userDto->password);

        self::assertNotNull($userDto->createdAt);
        self::assertNotNull($userDto->updatedAt);
        self::assertNotNull($userDto->id);
    }

    public function testUserDtoToArray(): void
    {
        $userEntity = UserFaker::new();

        self::assertNotNull($userEntity);
        self::assertInstanceOf(User::class, $userEntity);

        $userDto = UserDto::fromArray($userEntity);

        self::assertNotNull($userDto);
        self::assertInstanceOf(UserDto::class, $userDto);

        $userArray = $userDto->toArray();

        self::assertIsArray($userArray, 'UserDto::toArray() should return an array');

        self::assertCount(6, $userArray, 'Your array should have 6 elements');
        self::assertArrayHasKey('id', $userArray, 'Your array should have an id key');
        self::assertArrayHasKey('username', $userArray, 'Your array should have a firstname key');
        self::assertArrayHasKey('email', $userArray, 'Your array should have a lastname key');
        self::assertArrayHasKey('password', $userArray, 'Your array should have a number key');
        self::assertArrayHasKey('createdAt', $userArray, 'Your array should have a createdAt key');
        self::assertArrayHasKey('updatedAt', $userArray, 'Your array should have an updatedAt key');

        self::assertSame($userDto->id, $userArray['id'], 'Both values are differents');
        self::assertSame($userDto->username, $userArray['username'], 'Both values are differents');
        self::assertSame($userDto->email, $userArray['email'], 'Both values are differents');
        self::assertSame($userDto->password, $userArray['password'], 'Both values are differents');
        self::assertSame($userDto->createdAt, $userArray['createdAt'], 'Both values are differents');
        self::assertSame($userDto->updatedAt, $userArray['updatedAt'], 'Both values are differents');
    }

    public function testUserDtoToListUser(): void
    {
        $user1 = new User(
            username: new UserUsername('Mallorie'),
            email: new UserEmail('zimmermann@gmail.com'),
            password: new UserPassword('Zimmermann10?'),
        );

        $user2 = new User(
            username: new UserUsername('Mathis'),
            email: new UserEmail('boisson@gmail.com'),
            password: new UserPassword('Boisson10?'),
        );

        $user3 = new User(
            username: new UserUsername('John'),
            email: new UserEmail('Haimez'),
            password: new UserPassword('Haimez10?'),
        );

        self::assertInstanceOf(User::class, $user1);
        self::assertInstanceOf(User::class, $user2);
        self::assertInstanceOf(User::class, $user3);

        $userArray = [$user1, $user2, $user3];

        self::assertIsArray($userArray);

        $userDto = UserDtoFaker::new();

        self::assertInstanceOf(UserDto::class, $userDto);

        $listUser = $userDto::toListUser($userArray);

        self::assertIsArray($listUser);

        self::assertCount(3, $listUser);

        self::assertInstanceOf(UserDto::class, $listUser[0]);
        self::assertInstanceOf(UserDto::class, $listUser[1]);
        self::assertInstanceOf(UserDto::class, $listUser[2]);
    }
}
