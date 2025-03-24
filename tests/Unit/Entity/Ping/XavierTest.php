<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Entity\Xavier;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\Xavier\Xavier;
use Tocda\Tests\Faker\Entity\Xavier\XavierFaker;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[CoversClass(Xavier::class)]
class XavierTest extends TocdaUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $firstname = 'John';
        $lastname = 'Doe';
        $number = 0;

        $xavier = XavierFaker::new();

        self::assertSame($firstname, $xavier->firstname());
        self::assertSame($lastname, $xavier->lastname());
        self::assertSame($number, $xavier->number());
        self::assertNotNull($xavier->createdAt());
        self::assertNotNull($xavier->updatedAt());
    }

    public function testEntitySetters(): void
    {
        $xavier = XavierFaker::new();

        $newDateUpdated = new DateTimeImmutable();
        $xavier->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $xavier->updatedAt());
    }
}
