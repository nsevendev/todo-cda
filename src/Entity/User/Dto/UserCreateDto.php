<?php

declare(strict_types=1);

namespace Tocda\Entity\User\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class UserCreateDto // "readonly" = rend l'élément non mutable (immutable)
{
    public function __construct(
        #[Assert\NotBlank(message: 'Le username est requis.')]
        #[Assert\Length(max: 25, maxMessage: 'Le prénom doit contenir au plus {{ limit }} caractères.')]
        public string $username,
        #[Assert\NotBlank(message: 'Le mail est requis.')]
        #[Assert\Email(message: 'Le mail "{{ value }}" n\'est pas un email valide.')]
        public string $email,
        #[Assert\NotBlank(message: 'Le mot de passe est requis.')]
        #[Assert\Length(min: 8, minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères.')]
        #[Assert\Regex(
            pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            message: 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.',
        )]
        public string $password,
    ) {}
    
    public static function new(string $username, string $email, string $password): self
    {
        return new self(
            username: $username,
            email: $email,
            password: $password,
        );
    }
}