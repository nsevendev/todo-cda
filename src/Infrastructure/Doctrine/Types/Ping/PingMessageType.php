<?php

declare(strict_types=1);

namespace Tocda\Infrastructure\Doctrine\Types\Ping;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Tocda\Entity\Ping\ValueObject\PingMessage;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Ping\PingInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;

final class PingMessageType extends Type
{
    public function getName(): string
    {
        return 'app_ping_message';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws PingInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?PingMessage
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new PingInvalidArgumentException(getMessage: 'Ping message doit être une chaine de caractères', errors: [Error::create(key: 'PingMessageType', message: 'Ping message doit être une chaine de caractères')]);
        }

        return PingMessage::fromValue($value);
    }

    /**
     * @throws PingInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof PingMessage) {
            throw new PingInvalidArgumentException(getMessage: 'La valeur doit etre une instance de PingMessage', errors: [Error::create(key: 'PingMessageType', message: 'La valeur doit etre une instance de PingMessage')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
