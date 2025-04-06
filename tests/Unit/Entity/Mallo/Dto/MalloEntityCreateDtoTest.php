<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Entity\Mallo\Dto;

use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\Mallo\Dto\MalloCreateDto;
use Tocda\Entity\Mallo\ValueObject\MalloFirstname;
use Tocda\Entity\Mallo\ValueObject\MalloLastname;
use Tocda\Entity\Mallo\ValueObject\MalloNumber;
use Tocda\Tests\Faker\Dto\Mallo\MalloCreateDtoFaker;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[CoversClass(MalloCreateDto::class), CoversClass(MalloFirstname::class), CoversClass(MalloLastname::class), CoversClass(MalloNumber::class)]
class MalloEntityCreateDtoTest extends TocdaUnitTestCase
{
    public function testMalloEntityCreateDto(): void
    {
        $malloEntityCreateDto = MalloCreateDtoFaker::new(); // On utilise MalloCreateDtoFaker pour générer un faux objet MalloCreateDto (class qui représente l'object) et on le stocke dans $malloEntityCreateDto.

        self::assertNotNull($malloEntityCreateDto);

        self::assertInstanceOf(MalloCreateDto::class, $malloEntityCreateDto);

        self::assertSame('John', $malloEntityCreateDto->firstname); // On teste si la propriéte firstname de $malloEntityCreateDto (qui appartient à MalloCreateDto) contient bien la bonne valeur ('John')
        self::assertSame('Doe', $malloEntityCreateDto->lastname);
        self::assertSame(13, $malloEntityCreateDto->number);

        self::assertSame('John', (string) $malloEntityCreateDto->firstname); // On teste si la propriéte firstname de $malloEntityCreateDto (qui appartient à MalloCreateDto) contient bien la bonne valeur ('Mallo') et le bon type (string)
        self::assertSame('Doe', (string) $malloEntityCreateDto->lastname);
        self::assertSame('13', (string) $malloEntityCreateDto->number);
    }

    public function testMalloEntityCreateDtoWithFunctionNew(): void
    {
        $malloEntityCreateDto = MalloCreateDto::new(
            'John',
            'Doe',
            13
        );

        self::assertNotNull($malloEntityCreateDto);

        self::assertInstanceOf(MalloCreateDto::class, $malloEntityCreateDto);

        self::assertSame('John', $malloEntityCreateDto->firstname);
        self::assertSame('Doe', $malloEntityCreateDto->lastname);
        self::assertSame(13, $malloEntityCreateDto->number);

        self::assertSame('John', (string) $malloEntityCreateDto->firstname);
        self::assertSame('Doe', (string) $malloEntityCreateDto->lastname);
        self::assertSame('13', (string) $malloEntityCreateDto->number);
    }
}
