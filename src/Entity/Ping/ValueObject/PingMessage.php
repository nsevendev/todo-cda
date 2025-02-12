<?php

declare(strict_types=1);

namespace Tocda\Entity\Ping\ValueObject;

use Stringable;
use Symfony\Component\Validator\Constraints as Assert;
use Tocda\Infrastructure\Shared\Type\ValueObjectInterface;

readonly class PingMessage implements Stringable, ValueObjectInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'Le message est requis.')]
        #[Assert\Length(max: 255, maxMessage: 'Le message doit contenir au plus {{ limit }} caractères.')]
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
