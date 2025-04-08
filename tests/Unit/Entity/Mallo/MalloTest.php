<?php

declare(strict_types=1); // Déclare le typage strict

namespace Tocda\Tests\Unit\Entity\Mallo;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\Mallo\Mallo;
use Tocda\Entity\Mallo\ValueObject\MalloFirstname;
use Tocda\Entity\Mallo\ValueObject\MalloLastname;
use Tocda\Entity\Mallo\ValueObject\MalloNumber;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mallo\MalloInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\Doctrine\Types\Mallo\MalloFirstnameType;
use Tocda\Infrastructure\Doctrine\Types\Mallo\MalloLastnameType;
use Tocda\Infrastructure\Doctrine\Types\Mallo\MalloNumberType;
use Tocda\Tests\Faker\Entity\Mallo\MalloFaker;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[
    CoversClass(Mallo::class),
    CoversClass(MalloFirstname::class),
    CoversClass(MalloFirstnameType::class),
    CoversClass(MalloLastname::class),
    CoversClass(MalloLastnameType::class),
    CoversClass(MalloNumber::class),
    CoversClass(MalloNumberType::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(MalloInvalidArgumentException::class),
    CoversClass(Error::class),
] // Définie les classes à couvrir
class MalloTest extends TocdaUnitTestCase // Définie la classe MalloTest qui hérite de la classe TocdaUnitTestCase
{
    /**
     * @throws MalloInvalidArgumentException
     */
    public function testEntityInitialization(): void // Définie la méthode testEntityInitialization
    {
        $firstname = 'John'; // Définie la variable firstname
        $lastname = 'Doe';
        $number = 13; // Définie la variable number

        $mallo = MalloFaker::new(); // Crée une nouvelle instance de la classe Mallo (:: = fonction statique)

        self::assertSame($firstname, $mallo->firstname()->value()); // Vérifie que $firstname est égal à $mallo->firstname()
        self::assertSame($lastname, $mallo->lastname()->value()); // La méthode assertSame est une méthode qu'on hérite de la classe TestCase de PHPUnit
        self::assertSame($number, $mallo->number()->value()); // !!!!!! $mallo->number() appelle une fonction publique (méthode) de l'instance de classe Mallo contenu dans $mallo et elle retourne la propriété number de cette instance
        self::assertSame($firstname, $mallo->firstname()->jsonSerialize());
        self::assertSame($lastname, $mallo->lastname()->jsonSerialize());
        self::assertSame($number, $mallo->number()->jsonSerialize());
        self::assertSame($firstname, (string) $mallo->firstname());
        self::assertSame($lastname, (string) $mallo->lastname());
        self::assertSame((string) $number, (string) $mallo->number());
        self::assertNotNull($mallo->createdAt());
        self::assertNotNull($mallo->updatedAt());
        self::assertNotNull($mallo->id());
    }

    /**
     * @throws MalloInvalidArgumentException
     */
    public function testEntitySetters(): void // Définie la méthode testEntitySetters
    {
        $mallo = MalloFaker::new(); // Crée une nouvelle variable $mallo qui contient une nouvelle instance de la classe Mallo qui est créee avec la méthode new de la classe MalloFaker

        $newDateUpdated = new DateTimeImmutable(); // Crée une nouvelle instance de la classe DateTimeImmutable
        $mallo->setUpdatedAt($newDateUpdated); // Appelle la méthode setUpdatedAt de la classe Mallo avec $newDateUpdated en paramètre

        self::assertSame($newDateUpdated, $mallo->updatedAt()); // Vérifie que $newDateUpdated est égal à $mallo->updatedAt()
    }

    public function testEntityWithFirstnameMoreLonger(): void
    {
        $this->expectException(MalloInvalidArgumentException::class);

        $mallo = MalloFaker::withFirstnameMoreLonger();
    }

    public function testEntityWithFirstnameEmpty(): void
    {
        $this->expectException(MalloInvalidArgumentException::class);

        $mallo = MalloFaker::withFirstnameEmpty();
    }
}
