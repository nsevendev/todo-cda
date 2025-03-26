<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Entity\Ping;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\Ping\Ping;
use Tocda\Entity\Ping\ValueObject\PingMessage;
use Tocda\Entity\Ping\ValueObject\PingStatus;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Ping\PingInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\Doctrine\Types\Ping\PingMessageType;
use Tocda\Infrastructure\Doctrine\Types\Ping\PingStatusType;
use Tocda\Tests\Faker\Entity\Ping\PingFaker;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[
    CoversClass(Ping::class),
    CoversClass(PingMessage::class),
    CoversClass(PingMessageType::class),
    CoversClass(PingStatus::class),
    CoversClass(PingStatusType::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(PingInvalidArgumentException::class),
    CoversClass(Error::class),
]
class PingTest extends TocdaUnitTestCase
{
    /**
     * @throws PingInvalidArgumentException
     */
    public function testEntityInitialization(): void
    {
        $status = 200;
        $message = 'Le ping à réussi';

        $ping = PingFaker::new();

        self::assertSame($status, $ping->status()->value());
        self::assertSame($message, $ping->message()->value());
        self::assertSame($status, $ping->status()->jsonSerialize());
        self::assertSame($message, $ping->message()->jsonSerialize());
        self::assertSame((string) $status, (string) $ping->status());
        self::assertSame($message, (string) $ping->message());
        self::assertNotNull($ping->createdAt());
        self::assertNotNull($ping->updatedAt());
    }

    /**
     * @throws PingInvalidArgumentException
     */
    public function testEntitySetters(): void
    {
        $ping = PingFaker::new();

        $newDateUpdated = new DateTimeImmutable();
        $ping->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $ping->updatedAt());
    }

    public function testEntityWithMessageMoreLonger(): void
    {
        $this->expectException(PingInvalidArgumentException::class);

        $ping = PingFaker::withMessageMoreLonger();
    }

    public function testEntityWithMessageEmpty(): void
    {
        $this->expectException(PingInvalidArgumentException::class);

        $ping = PingFaker::withMessageEmpty();
    }
}
