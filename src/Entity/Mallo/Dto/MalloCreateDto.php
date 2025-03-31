<?php

declare(strict_types=1);

namespace Tocda\Entity\Mallo\Dto; 

use Symfony\Component\Validator\Constraints as Assert;
use Tocda\Entity\Mallo\ValueObject\MalloFirstname;
use Tocda\Entity\Mallo\ValueObject\MalloLastname;
use Tocda\Entity\Mallo\ValueObject\MalloNumber;

readonly class MalloCreateDto // "readonly" = rend l'élément  non mutable (immutable)
{
    public function __construct(// Constructeur de la class
        #[Assert\Valid]
        private MalloFirstname $firstname,
        #[Assert\Valid]
        private MalloLastname $lastname,
        #[Assert\Valid]
        private MalloNumber $number, 
    ) {}

    public static function new(string $firstname, string $lastname, int $number): self 
    {
        return new self(
            firstname: MalloFirstname::fromValue($firstname), // Appelle la méthode fromValue de la classe PingStatus avec $status en paramètre
            lastname: MalloLastname::fromValue($lastname), // Appelle la méthode fromValue de la classe PingMessage avec $message en paramètre
            number: MalloNumber::fromValue($number),
        );
    }

    public function firstname(): MalloFirstname // Fonction qui retourne le firstname 
    {
        return $this->firstname; // On retourne le firstname
    }

    public function lastname(): MalloLastname 
    {
        return $this->lastname;
    }

    public function number(): MalloNumber 
    {
        return $this->number;
    }
}
