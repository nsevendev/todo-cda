<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Entity\User;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\User\User;
use Tocda\Entity\User\ValueObject\UserEmail;
use Tocda\Entity\User\ValueObject\UserPassword;
use Tocda\Entity\User\ValueObject\UserUsername;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\User\UserInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\Doctrine\Types\User\UserEmailType;
use Tocda\Infrastructure\Doctrine\Types\User\UserPasswordType;
use Tocda\Infrastructure\Doctrine\Types\User\UserUsernameType;
use Tocda\Tests\Faker\Entity\User\UserFaker;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[
    CoversClass(User::class),
    CoversClass(UserEmail::class),
    CoversClass(UserEmailType::class),
    CoversClass(UserPassword::class),
    CoversClass(UserPasswordType::class),
    CoversClass(UserUsername::class),
    CoversClass(UserUsernameType::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(UserInvalidArgumentException::class),
    CoversClass(Error::class),
]
class UserTest extends TocdaUnitTestCase
{
    /**
     * @throws UserInvalidArgumentException
     */
    public function testEntityInitialization(): void
    {
        $username = 'paquito';
        $email = 'paquito@gmail.com';
        $password = 'Paquito123?';

        $user = UserFaker::new();

        self::assertSame($username, $user->username()->value());
        self::assertSame($email, $user->email()->value());
        self::assertSame($password, $user->password()->value());
        self::assertSame($username, $user->username()->jsonSerialize());
        self::assertSame($email, $user->email()->jsonSerialize());
        self::assertSame($password, $user->password()->jsonSerialize());
        self::assertSame((string) $username, (string) $user->username());
        self::assertSame($email, (string) $user->email());
        self::assertSame($password, (string) $user->password());
        self::assertNotNull($user->createdAt());  
        self::assertNotNull($user->updatedAt());
    }

    /**
     * @throws UserInvalidArgumentException
     */
    public function testEntitySetters(): void
    {
        $user = UserFaker::new();

        $newDateUpdated = new DateTimeImmutable();
        $user->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $user->updatedAt());
    }

    public function testEntityWithMessageMoreLonger(): void
    {
        $this->expectException(UserInvalidArgumentException::class);

        $user = UserFaker::withMessageMoreLonger();
    }

    public function testEntityWithMessageEmpty(): void
    {
        $this->expectException(UserInvalidArgumentException::class);

        $user = UserFaker::withMessageEmpty();
    }
}

