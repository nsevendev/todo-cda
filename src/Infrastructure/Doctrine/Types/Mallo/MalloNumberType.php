<?php

declare(strict_types=1);

namespace Tocda\Infrastructure\Doctrine\Types\Mallo;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Tocda\Entity\Mallo\ValueObject\MalloNumber;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mallo\MalloInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;

final class MalloNumberType extends Type
{
    public function getName(): string
    {
        return 'app_mallo_number';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    /**
     * @throws MalloInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?MalloNumber
    {
        if (null === $value) {
            return null;
        }

        if (false === is_int($value)) {
            throw new MalloInvalidArgumentException(getMessage: 'Mallo number doit être un chiffre', errors: [Error::create(key: 'MalloNumberType', message: 'Mallo number doit être un chiffre')]);
        }

        return MalloNumber::fromValue($value);
    }

    /**
     * @throws MalloInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof MalloNumber) {
            throw new MalloInvalidArgumentException(getMessage: 'La valeur doit etre une instance de MalloNumber', errors: [Error::create(key: 'MalloNumberType', message: 'La valeur doit etre une instance de MalloNumber')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
