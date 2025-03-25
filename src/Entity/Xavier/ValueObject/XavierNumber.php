<?php

declare(strict_types=1);

namespace Tocda\Entity\Xavier\ValueObject;

use Stringable;
use Symfony\Component\Validator\Constraints as Assert;
use Tocda\Infrastructure\Shared\Type\ValueObjectInterface;

readonly class XavierNumber implements Stringable, ValueObjectInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'La valeur ne peut pas être vide.')]
        private int $value,
    ) {}

    /**
     * Crée un objet XavierNumber à partir d'une valeur.
     */
    public static function fromValue(string|int|float|bool $value): self
    {
        // Convertir en entier, mais gérer les cas spéciaux si nécessaire
        return new self(value: (int) $value);
    }

    /**
     * Retourne la valeur stockée.
     */
    public function value(): int
    {
        return $this->value;
    }

    /**
     * Conversion en chaîne de caractères.
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }
}
