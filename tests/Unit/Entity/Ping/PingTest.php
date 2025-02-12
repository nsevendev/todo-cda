<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Entity\Ping;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\Ping\Ping;
use Tocda\Tests\Faker\Entity\Ping\PingFaker;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[CoversClass(Ping::class)]
class PingTest extends TocdaUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $status = 200;
        $message = 'Le ping à réussi';

        $ping = PingFaker::new();

        self::assertSame($status, $ping->status());
        self::assertSame($message, $ping->message());
        self::assertNotNull($ping->createdAt());
        self::assertNotNull($ping->updatedAt());
    }

    public function testEntitySetters(): void
    {
        $ping = PingFaker::new();

        $newDateUpdated = new DateTimeImmutable();
        $ping->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $ping->updatedAt());
    }
}
