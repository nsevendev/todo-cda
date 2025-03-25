<?php

declare(strict_types=1);

namespace Tocda\Entity\Xavier\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Tocda\Entity\Xavier\ValueObject\XavierFirstname;
use Tocda\Entity\Xavier\ValueObject\XavierLastname;
use Tocda\Entity\Xavier\ValueObject\XavierNumber;

readonly class XavierCreateDto
{
    public function __construct(
        #[Assert\Valid]
        private XavierFirstname $firstname,
        #[Assert\Valid]
        private XavierLastname $lastname,
        #[Assert\Valid]
        private XavierNumber $number,
    ) {}

    /**
     * Crée une nouvelle instance de XavierCreateDto à partir des données brutes.
     *
     * @param string $firstname Le prénom de Xavier.
     * @param string $lastname Le nom de Xavier.
     * @param int $number Le numéro de Xavier.
     *
     * @return self La nouvelle instance de XavierCreateDto.
     *
     * @throws \InvalidArgumentException Si les valeurs sont invalides.
     */
    public static function new(string $firstname, string $lastname, int $number): self
    {
        // Vérification ou gestion de la validité des données (si nécessaire).
        // Par exemple, tu pourrais avoir une logique pour valider ou nettoyer les chaînes et les nombres avant de les passer.

        return new self(
            firstname: XavierFirstname::fromValue($firstname),
            lastname: XavierLastname::fromValue($lastname),
            number: XavierNumber::fromValue($number),
        );
    }

    /**
     * Récupère le prénom de Xavier.
     *
     * @return XavierFirstname
     */
    public function firstname(): XavierFirstname
    {
        return $this->firstname;
    }

    /**
     * Récupère le nom de Xavier.
     *
     * @return XavierLastname
     */
    public function lastname(): XavierLastname
    {
        return $this->lastname;
    }

    /**
     * Récupère le numéro de Xavier.
     *
     * @return XavierNumber
     */
    public function number(): XavierNumber
    {
        return $this->number;
    }
}
