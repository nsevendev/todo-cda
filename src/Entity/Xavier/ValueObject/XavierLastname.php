<?php

declare(strict_types=1);

namespace Tocda\Entity\Xavier\ValueObject;

use Stringable;
use Symfony\Component\Validator\Constraints as Assert;
use Tocda\Infrastructure\Shared\Type\ValueObjectInterface;

readonly class XavierLastname implements Stringable, ValueObjectInterface
{
    public function __construct(
        #[Assert\NotBlank(lastname: 'Le lastname est requis.')]
        #[Assert\Choice(lastname: ['Doe'], message: 'Le lastname doit être de {{ choices }}')]
        private int $value,
    ) {}

    public static function fromValue(string|int|float|bool $value): self
    {
        return new self(value: (int) $value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
