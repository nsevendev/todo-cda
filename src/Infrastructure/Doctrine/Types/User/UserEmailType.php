<?php

declare(strict_types=1);    

namespace Tocda\Infrastructure\Doctrine\Types\User;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Tocda\Entity\User\ValueObject\UserEmail;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\User\UserInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;

final class UserEmailType extends Type
{
    public function getName(): string
    {
        return 'app_user_email';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }
    
    /**
     * @throws UserInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?UserEmail
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new UserInvalidArgumentException(getMessage: 'User email doit être une chaine de caractères', errors: [Error::create(key: 'UserEmailType', message: 'User email doit être une chaine de caractères')]);
        }

        return UserEmail::fromValue($value);
    }
    /**
     * @throws UserInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof UserEmail) {
            throw new UserInvalidArgumentException(getMessage: 'La valeur doit etre une instance de UserEmail', errors: [Error::create(key: 'UserEmailType', message: 'La valeur doit etre une instance de UserEmail')]);
        }

        return $value->value();
    }
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}