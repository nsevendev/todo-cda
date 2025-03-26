<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Entity\Ping\Dto;

use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\Ping\Dto\PingCreateDto;
use Tocda\Entity\Ping\ValueObject\PingMessage;
use Tocda\Entity\Ping\ValueObject\PingStatus;
use Tocda\Tests\Faker\Dto\Ping\PingCreateDtoFaker;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[CoversClass(PingCreateDto::class), CoversClass(PingStatus::class), CoversClass(PingMessage::class)]
class PingEntityCreateDtoTest extends TocdaUnitTestCase
{
    public function testPingEntityCreateDto(): void
    {
        $pingEntityCreateDto = PingCreateDtoFaker::new();

        self::assertNotNull($pingEntityCreateDto);

        self::assertInstanceOf(PingCreateDto::class, $pingEntityCreateDto);

        self::assertSame(200, $pingEntityCreateDto->status);
        self::assertSame('Le ping à réussi en faker', $pingEntityCreateDto->message);

        self::assertSame('200', (string) $pingEntityCreateDto->status);
        self::assertSame('Le ping à réussi en faker', (string) $pingEntityCreateDto->message);
    }

    public function testPingEntityCreateDtoWithFunctionNew(): void
    {
        $pingEntityCreateDto = PingCreateDto::new(
            200,
            'Le ping à réussi en faker'
        );

        self::assertNotNull($pingEntityCreateDto);

        self::assertInstanceOf(PingCreateDto::class, $pingEntityCreateDto);

        self::assertSame(200, $pingEntityCreateDto->status);
        self::assertSame('Le ping à réussi en faker', $pingEntityCreateDto->message);

        self::assertSame('200', (string) $pingEntityCreateDto->status);
        self::assertSame('Le ping à réussi en faker', (string) $pingEntityCreateDto->message);
    }
}
