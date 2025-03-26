<?php

declare(strict_types=1);

namespace Tocda\Infrastructure\Doctrine\Types\Ping;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Tocda\Entity\Ping\ValueObject\PingStatus;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Ping\PingInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;

final class PingStatusType extends Type
{
    public function getName(): string
    {
        return 'app_ping_status';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    /**
     * @throws PingInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?PingStatus
    {
        if (null === $value) {
            return null;
        }

        if (false === is_int($value)) {
            throw new PingInvalidArgumentException(getMessage: 'Ping status doit être un chiffre', errors: [Error::create(key: 'PingStatusType', message: 'Ping status doit être un chiffre')]);
        }

        return PingStatus::fromValue($value);
    }

    /**
     * @throws PingInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof PingStatus) {
            throw new PingInvalidArgumentException(getMessage: 'La valeur doit etre une instance de PingStatus', errors: [Error::create(key: 'PingStatusType', message: 'La valeur doit etre une instance de PingStatus')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
