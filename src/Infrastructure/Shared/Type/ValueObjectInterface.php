<?php

declare(strict_types=1);

namespace Tocda\Infrastructure\Shared\Type;

interface ValueObjectInterface
{
    public static function fromValue(string|int|float|bool $value): self;

    public function value(): string|int|float|bool;
}
