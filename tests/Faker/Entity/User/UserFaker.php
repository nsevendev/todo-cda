<?php

declare(strict_types=1);

namespace Tocda\Tests\Faker\Entity\User;

use Tocda\Entity\User\User;
use Tocda\Entity\User\ValueObject\UserEmail;
use Tocda\Entity\User\ValueObject\UserPassword;
use Tocda\Entity\User\ValueObject\UserUsername;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\User\UserInvalidArgumentException;

final class UserFaker
{
    /**
     * @throws UserInvalidArgumentException
     */
    public static function new(): User
    {
        return new User(
            username: UserUsername::fromValue('paquito'),
            email: UserEmail::fromValue('paquito@gmail.com'),
            password: UserPassword::fromValue('Paquito123?')
        );
    }

    /**
     * @throws UserInvalidArgumentException
     */
    public static function withMessageMoreLonger(): User
    {
        return new User(
            username: UserUsername::fromValue('paquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquitopaquito'),
            email: UserEmail::fromValue('paquito@gmail.com'),
            password: UserPassword::fromValue('Paquito123?')
        );
    }

    /**
     * @throws UserInvalidArgumentException
     */
    public static function withMessageEmpty(): User
    {
        return new User(
            username: UserUsername::fromValue(''),
            email: UserEmail::fromValue('paquito@gmail.com'),
            password: UserPassword::fromValue('paquito123')
        );
    }
}
