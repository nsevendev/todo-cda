<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\Mallo\ValueObject\MalloFirstname;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mallo\MalloInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\Doctrine\Types\Mallo\MalloFirstnameType;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[
    CoversClass(MalloFirstnameType::class),
    CoversClass(MalloFirstname::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(MalloInvalidArgumentException::class),
    CoversClass(Error::class),
]
final class MalloFirstnameTypeTest extends TocdaUnitTestCase
{
    private MalloFirstnameType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_mallo_firstname')) {
            Type::addType('app_mallo_lastname', MalloFirstnameType::class);
        }

        $this->type = Type::getType('app_mallo_firstname');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_mallo_firstname', $this->type->getName());
    }

    public function testGetSQLDeclaration(): void
    {
        $column = ['length' => 255];
        $sql = $this->type->getSQLDeclaration($column, $this->platform);
        self::assertSame('VARCHAR(255)', $sql);
    }

    /**
     * @throws MalloInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithValidString(): void
    {
        $malloMessage = $this->type->convertToPHPValue('Hello, World!', $this->platform);
        self::assertInstanceOf(MalloFirstname::class, $malloMessage);
        self::assertSame('Hello, World!', $malloMessage->value());
    }

    /**
     * @throws MalloInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithNull(): void
    {
        $malloMessage = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($malloMessage);
    }

    /**
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithInvalidType(): void
    {
        $this->expectException(MalloInvalidArgumentException::class);
        $this->type->convertToPHPValue(123, $this->platform);
    }

    /**
     * @throws MalloInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueWithValidPingMessage(): void
    {
        $malloMessage = MalloFirstname::fromValue('Hello, Database!');
        $dbValue = $this->type->convertToDatabaseValue($malloMessage, $this->platform);
        self::assertSame('Hello, Database!', $dbValue);
    }

    /**
     * @throws MalloInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueWithNull(): void
    {
        $dbValue = $this->type->convertToDatabaseValue(null, $this->platform);
        self::assertNull($dbValue);
    }

    /**
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueWithInvalidType(): void
    {
        $this->expectException(MalloInvalidArgumentException::class);
        $this->type->convertToDatabaseValue('Invalid Type', $this->platform);
    }

    public function testRequiresSQLCommentHint(): void
    {
        self::assertTrue($this->type->requiresSQLCommentHint($this->platform));
    }
}
