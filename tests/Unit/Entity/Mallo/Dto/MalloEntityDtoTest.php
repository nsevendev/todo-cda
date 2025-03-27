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

    public function testMalloDtoToArray(): void
    {
        $malloEntity = MalloFaker::new();

        self::assertNotNull($malloEntity);
        self::assertInstanceOf(Mallo::class, $malloEntity);

        $malloDto = MalloDto::fromArray($malloEntity);

        self::assertNotNull($malloDto);
        self::assertInstanceOf(MalloDto::class, $malloDto);

        $malloArray = $malloDto->toArray();

        self::assertIsArray($malloArray, 'MalloDto::toArray() should return an array');

        self::assertCount(6, $malloArray, 'Your array should have 6 elements');
        self::assertArrayHasKey('id', $malloArray, 'Your array should have an id key');
        self::assertArrayHasKey('firstname', $malloArray, 'Your array should have a firstname key');
        self::assertArrayHasKey('lastname', $malloArray, 'Your array should have a lastname key');
        self::assertArrayHasKey('number', $malloArray, 'Your array should have a number key');
        self::assertArrayHasKey('createdAt', $malloArray, 'Your array should have a createdAt key');
        self::assertArrayHasKey('updatedAt', $malloArray, 'Your array should have an updatedAt key');

        self::assertSame($malloDto->id, $malloArray['id'], 'Both values are differents');
        self::assertSame($malloDto->firstname, $malloArray['firstname'], 'Both values are differents');
        self::assertSame($malloDto->lastname, $malloArray['lastname'], 'Both values are differents');
        self::assertSame($malloDto->number, $malloArray['number'], 'Both values are differents');
        self::assertSame($malloDto->createdAt, $malloArray['createdAt'], 'Both values are differents');
        self::assertSame($malloDto->updatedAt, $malloArray['updatedAt'], 'Both values are differents');
    }
}
