<?php

declare(strict_types=1);

namespace Tocda\Entity\User\ValueObject;

use JsonSerializable;
use Stringable;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\User\UserInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;

readonly class UserPassword implements Stringable, JsonSerializable
{
    public function __construct(private string $value) {}

    /**
     * @throws UserInvalidArgumentException
     */
    public static function fromValue(string $value): self
    {
        $valueFormated = trim($value);

        if ('' === $valueFormated) {
            throw new UserInvalidArgumentException(getMessage: 'Password ne peux pas etre vide', errors: [Error::create(key: 'UserPassword', message: 'Password ne peux pas etre vide')]);
        }

        if (mb_strlen($valueFormated) > 255) {
            throw new UserInvalidArgumentException(getMessage: 'Password ne peux pas etre supérieur à 255 caractères', errors: [Error::create(key: 'UserPassword', message: 'Password ne peux pas etre supérieur à 255 caractères')]);
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