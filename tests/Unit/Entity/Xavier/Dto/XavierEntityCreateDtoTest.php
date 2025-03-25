<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Entity\Xavier\Dto;

use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\Xavier\Dto\XavierCreateDto;
use Tocda\Entity\Xavier\ValueObject\XavierFirstname;
use Tocda\Entity\Xavier\ValueObject\XavierLastname;
use Tocda\Entity\Xavier\ValueObject\XavierNumber;
use Tocda\Tests\Faker\Dto\Xavier\XavierCreateDtoFaker;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[CoversClass(XavierCreateDto::class), CoversClass(XavierFirstname::class), CoversClass(XavierLastname::class), CoversClass(XavierNumber::class)]
class XavierEntityCreateDtoTest extends TocdaUnitTestCase
{
    public function testXavierEntityCreateDto(): void
    {
        $xavierEntityCreateDto = XavierCreateDtoFaker::new();

        self::assertNotNull($xavierEntityCreateDto);
        self::assertInstanceOf(XavierCreateDto::class, $xavierEntityCreateDto);
        self::assertInstanceOf(XavierFirstname::class, $xavierEntityCreateDto->firstname());
        self::assertInstanceOf(XavierLastname::class, $xavierEntityCreateDto->lastname());
        self::assertInstanceOf(XavierNumber::class, $xavierEntityCreateDto->number());

        // var_dump($xavierEntityCreateDto->firstname()->value());
        // die();

        self::assertSame('John', (string) $xavierEntityCreateDto->firstname()->value());
        self::assertSame('Doe', (string) $xavierEntityCreateDto->lastname());
        self::assertSame(0, $xavierEntityCreateDto->number()->value());
    }

    public function testXavierEntityCreateDtoWithFunctionNew(): void
    {
        $xavierEntityCreateDto = XavierCreateDto::new('John', 'Doe', 0);

        self::assertNotNull($xavierEntityCreateDto);
        self::assertInstanceOf(XavierCreateDto::class, $xavierEntityCreateDto);
        self::assertInstanceOf(XavierFirstname::class, $xavierEntityCreateDto->firstname());
        self::assertInstanceOf(XavierLastname::class, $xavierEntityCreateDto->lastname());
        self::assertInstanceOf(XavierNumber::class, $xavierEntityCreateDto->number());

        self::assertSame('John', (string) $xavierEntityCreateDto->firstname()->value());
        self::assertSame('Doe', (string) $xavierEntityCreateDto->lastname());
        self::assertSame(0, $xavierEntityCreateDto->number()->value());
    }
}
