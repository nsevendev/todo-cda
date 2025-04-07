<?php

declare(strict_types=1);   

namespace Tocda\Infrastructure\Doctrine\Types\User;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Tocda\Entity\User\ValueObject\UserUsername;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\User\UserInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;

final class UserUsernameType extends Type
{
    public function getName(): string
    {
        return 'app_user_username';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }
    
    /**
     * @throws UserInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?UserUsername
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new UserInvalidArgumentException(getMessage: 'User username doit être une chaine de caractères', errors: [Error::create(key: 'UserUsernameType', message: 'User username doit être une chaine de caractères')]);
        }

        return UserUsername::fromValue($value);
    }
    /**
     * @throws UserInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof UserUsername) {
            throw new UserInvalidArgumentException(getMessage: 'La valeur doit etre une instance de UserUsername', errors: [Error::create(key: 'UserUsernameType', message: 'La valeur doit etre une instance de UserUsername')]);
        }

        return $value->value();
    }
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}