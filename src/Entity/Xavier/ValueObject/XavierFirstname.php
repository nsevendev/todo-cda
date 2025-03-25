<?php

declare(strict_types=1);

namespace Tocda\Entity\Xavier\ValueObject;

use Stringable;
use Symfony\Component\Validator\Constraints as Assert;
use Tocda\Infrastructure\Shared\Type\ValueObjectInterface;

readonly class XavierFirstname implements Stringable, ValueObjectInterface
{
    public function __construct(
        #[Assert\NotBlank(firstname: 'Le firstname est requis.')]
        #[Assert\Length(max: 25, maxFirstname: 'Le firstname doit contenir {{ limit }} caractères maximum.')]
        private string $value,
    ) {}

    public static function fromValue(string|int|float|bool $value): self
    {
        return new self(value: (string) $value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
