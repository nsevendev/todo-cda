<?php

declare(strict_types=1);

namespace Tocda\Tests\Faker\Dto\Xavier;

use Tocda\Entity\Xavier\Dto\XavierCreateDto;
use Tocda\Entity\Xavier\ValueObject\XavierMessage;
use Tocda\Entity\Xavier\ValueObject\XavierStatus;

class XavierCreateDtoFaker
{
    public static function new(): XavierCreateDto
    {
        return new XavierCreateDto(
            XavierFirstname::fromValue('John'),
            XavierLastname::fromValue('Doe'),
            XavierNumber::fromValue(0)
        );
    }
}
