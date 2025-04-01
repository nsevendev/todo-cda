<?php

declare(strict_types=1);

namespace Tocda\Entity\Mallo\ValueObject;

use Stringable;
use Symfony\Component\Validator\Constraints as Assert;
use Tocda\Infrastructure\Shared\Type\ValueObjectInterface;

readonly class MalloNumber implements Stringable, ValueObjectInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'Le number est requis.')]
        #[Assert\Range(min: 0, max: 100, notInRangeMessage: 'Le message doit contenir au plus {{ limit }} caractères.')]
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
