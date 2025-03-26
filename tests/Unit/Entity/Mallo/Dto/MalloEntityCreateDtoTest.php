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

        self::assertInstanceOf(MalloCreateDto::class, $malloEntityCreateDto); // On veut vérifier que $malloEntityCreateDto est bien une instance de la classe MalloCreateDto.
        self::assertInstanceOf(MalloFirstname::class, $malloEntityCreateDto->firstname());
        self::assertInstanceOf(MalloLastname::class, $malloEntityCreateDto->lastname());
        self::assertInstanceOf(MalloNumber::class, $malloEntityCreateDto->number());

        self::assertSame('Mallo', (string) $malloEntityCreateDto->firstname()); // On teste si la propriéte firstname de $malloEntityCreateDto (qui appartient à MalloCreateDto) contient bien la bonne valeur ('Mallo') et le bon type (string)
        self::assertSame('Zimmermann', (string) $malloEntityCreateDto->lastname());
        self::assertSame('67', (string) $malloEntityCreateDto->number());

        self::assertSame('Mallo', $malloEntityCreateDto->firstname()->value()); //  On teste si la vraie valeur contenue dans l’objet MalloFirstname est bien 'Mallo' et qu’elle est bien une string (sans conversion en string avec (string)
        self::assertSame('Zimmermann', $malloEntityCreateDto->lastname()->value());
        self::assertSame(67, $malloEntityCreateDto->number()->value());
    }

    public function testMalloEntityCreateDtoWithFunctionNew(): void
    {
        $malloEntityCreateDto = MalloCreateDto::new(
            'Mallo',
            'Zimmermann',
            67
        );

        self::assertNotNull($malloEntityCreateDto);

        self::assertInstanceOf(MalloCreateDto::class, $malloEntityCreateDto);
        self::assertInstanceOf(MalloFirstname::class, $malloEntityCreateDto->firstname());
        self::assertInstanceOf(MalloLastname::class, $malloEntityCreateDto->lastname());
        self::assertInstanceOf(MalloNumber::class, $malloEntityCreateDto->number());

        self::assertSame('Mallo', $malloEntityCreateDto->firstname()->value());
        self::assertSame('Zimmermann', $malloEntityCreateDto->lastname()->value());
        self::assertSame(67, $malloEntityCreateDto->number()->value());
    }
}
