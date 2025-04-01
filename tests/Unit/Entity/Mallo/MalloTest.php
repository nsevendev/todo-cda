<?php

declare(strict_types=1); // Déclare le typage strict

namespace Tocda\Tests\Unit\Entity\Mallo;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\Mallo\Mallo;
use Tocda\Tests\Faker\Entity\Mallo\MalloFaker;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[CoversClass(Mallo::class)] // Définie les classes à couvrir
class MalloTest extends TocdaUnitTestCase // Définie la classe MalloTest qui hérite de la classe TocdaUnitTestCase
{
    public function testEntityInitialization(): void // Définie la méthode testEntityInitialization
    {
        $firstname = 'Mallo'; // Définie la variable firstname
        $lastname = 'Zimmermann';
        $number = 67; // Définie la variable number

        $mallo = MalloFaker::new(); // Crée une nouvelle instance de la classe Mallo (:: = fonction statique)

        self::assertSame($firstname, $mallo->firstname()); // Vérifie que $firstname est égal à $mallo->firstname()
        self::assertSame($lastname, $mallo->lastname()); // La méthode assertSame est une méthode qu'on hérite de la classe TestCase de PHPUnit
        self::assertSame($number, $mallo->number()); // !!!!!! $mallo->number() appelle une fonction publique (méthode) de l'instance de classe Mallo contenu dans $mallo et elle retourne la propriété number de cette instance
        self::assertNotNull($mallo->createdAt()); // Vérifie que $mallo->createdAt() n'est pas null
        self::assertNotNull($mallo->updatedAt()); // Vérifie que $mallo->updatedAt() n'est pas null
        self::assertNotNull($mallo->id()); // Vérifie que $mallo->id() n'est pas null
    }

    public function testEntitySetters(): void // Définie la méthode testEntitySetters
    {
        $mallo = MalloFaker::new(); // Crée une nouvelle variable $mallo qui contient une nouvelle instance de la classe Mallo qui est créee avec la méthode new de la classe MalloFaker

        $newDateUpdated = new DateTimeImmutable(); // Crée une nouvelle instance de la classe DateTimeImmutable
        $mallo->setUpdatedAt($newDateUpdated); // Appelle la méthode setUpdatedAt de la classe Mallo avec $newDateUpdated en paramètre

        self::assertSame($newDateUpdated, $mallo->updatedAt()); // Vérifie que $newDateUpdated est égal à $mallo->updatedAt()
    }
}
