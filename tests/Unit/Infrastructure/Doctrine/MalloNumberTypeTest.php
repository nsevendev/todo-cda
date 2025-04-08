<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\Mallo\ValueObject\MalloNumber;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mallo\MalloInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\Doctrine\Types\Mallo\MalloNumberType;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[
    CoversClass(MalloNumberType::class),
    CoversClass(MalloNumber::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(MalloInvalidArgumentException::class),
    CoversClass(Error::class),
]
final class MalloNumberTypeTest extends TocdaUnitTestCase
{
    private MalloNumberType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_mallo_number')) {
            Type::addType('app_mallo_number', MalloNumberType::class);
        }

        $this->type = Type::getType('app_mallo_number');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_mallo_number', $this->type->getName());
    }

    public function testGetSQLDeclaration(): void
    {
        $column = [];
        $sql = $this->type->getSQLDeclaration($column, $this->platform);
        self::assertSame('INT', $sql);
    }

    /**
     * @throws MalloInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithValidString(): void
    {
        $pingStatus = $this->type->convertToPHPValue(200, $this->platform);
        self::assertInstanceOf(MalloNumber::class, $pingStatus);
        self::assertSame(200, $pingStatus->value());
    }

    /**
     * @throws MalloInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithNull(): void
    {
        $pingStatus = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($pingStatus);
    }

    /**
     * @throws MalloInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithInvalidType(): void
    {
        $this->expectException(MalloInvalidArgumentException::class);
        $this->type->convertToPHPValue('123', $this->platform);
    }

    /**
     * @throws MalloInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueWithValidPingMessage(): void
    {
        $pingStatus = MalloNumber::fromValue(200);
        $dbValue = $this->type->convertToDatabaseValue($pingStatus, $this->platform);
        self::assertSame(200, $dbValue);
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
