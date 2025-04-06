<?php

// dire à Doctrine comment stocker et récupérer un objet MalloMessage en base de données
// traducteur entre PHP et la base de données pour le type ...
declare(strict_types=1);

namespace Tocda\Infrastructure\Doctrine\Types\Mallo;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Tocda\Entity\Mallo\ValueObject\MalloLastname;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mallo\MalloInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;

final class MalloLastnameType extends Type
{
    public function getName(): string
    {
        return 'app_mallo_lastname';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws MalloInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?MalloLastname
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new MalloInvalidArgumentException(getMessage: 'Mallo lastname doit être une chaine de caractères', errors: [Error::create(key: 'MalloLastnameType', message: 'Mallo lastname doit être une chaine de caractères')]);
        }

        return MalloLastname::fromValue($value);
    }

    /**
     * @throws MalloInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof MalloLastname) {
            throw new MalloInvalidArgumentException(getMessage: 'La valeur doit etre une instance de MalloLastname', errors: [Error::create(key: 'MalloLastnameType', message: 'La valeur doit etre une instance de MalloLastname')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
