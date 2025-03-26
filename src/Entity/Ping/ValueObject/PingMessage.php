<?php

declare(strict_types=1);

namespace Tocda\Entity\Ping\ValueObject;

use JsonSerializable;
use Stringable;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Ping\PingInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;

readonly class PingMessage implements Stringable, JsonSerializable
{
    public function __construct(private string $value) {}

    /**
     * @throws PingInvalidArgumentException
     */
    public static function fromValue(string $value): self
    {
        $valueFormated = trim($value);

        if ('' === $valueFormated) {
            throw new PingInvalidArgumentException(getMessage: 'Ping message ne peux pas etre vide', errors: [Error::create(key: 'PingMessage', message: 'Ping message ne peux pas etre vide')]);
        }

        if (mb_strlen($valueFormated) > 255) {
            throw new PingInvalidArgumentException(getMessage: 'Ping message ne peux pas etre supérieur à 255 caractères', errors: [Error::create(key: 'PingMessage', message: 'Ping message ne peux pas etre supérieur à 255 caractères')]);
        }

        return new self(value: $valueFormated);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
