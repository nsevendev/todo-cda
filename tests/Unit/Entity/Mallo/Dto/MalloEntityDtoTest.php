<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Entity\Mallo\Dto;

use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\Mallo\Dto\MalloDto;
use Tocda\Entity\Mallo\Mallo;
use Tocda\Tests\Faker\Entity\Mallo\MalloFaker;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[CoversClass(Mallo::class), CoversClass(MalloDto::class)]
class MalloEntityDtoTest extends TocdaUnitTestCase
{
    public function testMalloDtoFromArray(): void
    {
        $malloEntity = MalloFaker::new();

        self::assertSame('Mallo', $malloEntity->firstname());
        self::assertSame('Zimmermann', $malloEntity->lastname());
        self::assertSame(67, $malloEntity->number());

        self::assertNotNull($malloEntity);
        self::assertInstanceOf(Mallo::class, $malloEntity);

        $malloDto = MalloDto::fromArray($malloEntity);

        self::assertNotNull($malloDto);
        self::assertInstanceOf(MalloDto::class, $malloDto);

        self::assertSame('Mallo', $malloDto->firstname);
        self::assertSame('Zimmermann', $malloDto->lastname);
        self::assertSame(67, $malloDto->number);

        self::assertNotNull($malloDto->createdAt);
        self::assertNotNull($malloDto->updatedAt);
        self::assertNotNull($malloDto->id);
    }
}
