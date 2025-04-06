<?php

declare(strict_types=1);

namespace Tocda\Entity\Mallo\ValueObject;

use JsonSerializable;
use Stringable;

readonly class MalloNumber implements Stringable, JsonSerializable
{
    public function __construct(private int $value) {}

    public static function fromValue(int $value): self
    {
        return new self(value: $value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function jsonSerialize(): int
    {
        return $this->value;
    }
}
