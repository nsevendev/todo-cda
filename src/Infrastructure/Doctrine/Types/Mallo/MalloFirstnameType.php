<?php

// dire à Doctrine comment stocker et récupérer un objet MalloMessage en base de données
// traducteur entre PHP et la base de données pour le type ...
declare(strict_types=1);

namespace Tocda\Infrastructure\Doctrine\Types\Mallo;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Tocda\Entity\Mallo\ValueObject\MalloFirstname;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mallo\MalloInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;

final class MalloFirstnameType extends Type
{
    public function getName(): string
    {
        return 'app_mallo_firstname';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws MalloInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?MalloFirstname
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new MalloInvalidArgumentException(getMessage: 'Mallo firstname doit être une chaine de caractères', errors: [Error::create(key: 'MalloFirstnameType', message: 'Mallo firstname doit être une chaine de caractères')]);
        }

        return MalloFirstname::fromValue($value);
    }

    /**
     * @throws MalloInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof MalloFirstname) {
            throw new MalloInvalidArgumentException(getMessage: 'La valeur doit etre une instance de MalloFirstname', errors: [Error::create(key: 'MalloFirstnameType', message: 'La valeur doit etre une instance de MalloFirstname')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
