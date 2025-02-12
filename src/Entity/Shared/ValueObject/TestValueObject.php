<?php

declare(strict_types=1);

namespace Tocda\Entity\Shared\ValueObject;

use Tocda\Infrastructure\Shared\Type\ValueObjectInterface;

class TestValueObject implements ValueObjectInterface
{
    private bool|float|int|string $value;

    public static function fromValue(string|int|float|bool $value): self
    {
        $instance = new self();
        $instance->value = $value;

        return $instance;
    }

    public function value(): bool|float|int|string
    {
        return $this->value;
    }
}
