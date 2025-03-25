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

    public static function new(string $firstname, string $lastname, int $number): self
    {
        return new self(
            firstname: XavierFirstname::fromValue($firstname),
            lastname: XavierLastname::fromValue($lastname),
            number: XavierNumber::fromValue($number),
        );
    }

    public function firstname(): XavierFirstname
    {
        return $this->firstname;
    }

    public function lastname(): XavierLastname
    {
        return $this->lastname;
    }

    public function number(): XavierNumber
    {
        return $this->number;
    }
}
