<?php

declare(strict_types=1);

namespace Tocda\Entity\Mallo\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class MalloCreateDto // "readonly" = rend l'élément non mutable (immutable)
{
    public function __construct(// Constructeur de la class
        #[Assert\NotBlank(message: 'Le prénom est requis.')]
        #[Assert\Length(max: 255, maxMessage: 'Le prénom doit contenir au plus {{ limit }} caractères.')]
        public string $firstname,
        #[Assert\NotBlank(message: 'Le nom est requis.')]
        #[Assert\Length(max: 255, maxMessage: 'Le nom doit contenir au plus {{ limit }} caractères.')]
        public string $lastname,
        #[Assert\NotBlank(message: 'Le numéro est requis.')]
        #[Assert\Range(min: 0, max: 100, notInRangeMessage: 'Le nombre doit être compris entre {{ min }} et {{ max }}.', )]
        public int $number,
    ) {}

    public static function new(string $firstname, string $lastname, int $number): self
    {
        return new self(
            firstname: $firstname,
            lastname: $lastname,
            number: $number,
        );
    }
}
