<?php

declare(strict_types=1);

namespace Tocda\Infrastructure\Doctrine\Types\User;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Tocda\Entity\User\ValueObject\UserPassword;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\User\UserInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;

final class UserPasswordType extends Type
{
    public function getName(): string
    {
        return 'app_user_password';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws UserInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?UserPassword
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new UserInvalidArgumentException(getMessage: 'User password doit être une chaine de caractères', errors: [Error::create(key: 'UserPasswordType', message: 'User password doit être une chaine de caractères')]);
        }

        return UserPassword::fromValue($value);
    }

    /**
     * @throws UserInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof UserPassword) {
            throw new UserInvalidArgumentException(getMessage: 'La valeur doit etre une instance de UserPassword', errors: [Error::create(key: 'UserPasswordType', message: 'La valeur doit etre une instance de UserPassword')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
