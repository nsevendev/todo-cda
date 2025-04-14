<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\User\ValueObject\UserPassword;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\User\UserInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\Doctrine\Types\User\UserPasswordType;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[
    CoversClass(UserPasswordType::class),
    CoversClass(UserPassword::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(UserInvalidArgumentException::class),
    CoversClass(Error::class),
]
final class UserPasswordTypeTest extends TocdaUnitTestCase
{
    private UserPasswordType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_user_password')) {
            Type::addType('app_user_password', UserPasswordType::class);
        }

        $this->type = Type::getType('app_user_password');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_user_password', $this->type->getName());
    }

    public function testGetSQLDeclaration(): void
    {
        $column = ['length' => 255];
        $sql = $this->type->getSQLDeclaration($column, $this->platform);
        self::assertSame('VARCHAR(255)', $sql);
    }

    public function testConvertToPHPValueWithValidString(): void
    {
        $userUsername = $this->type->convertToPHPValue('paquito@gmail.com', $this->platform);
        self::assertInstanceOf(UserPassword::class, $userUsername);
        self::assertSame('paquito@gmail.com', $userUsername->value());
    }

    public function testConvertToPHPValueWithNull(): void
    {
        $userEmail = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($userEmail);
    }

    public function testConvertToPHPValueWithInvalidType(): void
    {
        $this->expectException(UserInvalidArgumentException::class);
        $this->type->convertToPHPValue(123, $this->platform);
    }

    public function testConvertToDatabaseValueWithValidUserUsername(): void
    {
        $userUsername = new UserPassword('paquito@gmail.com');
        $dbValue = $this->type->convertToDatabaseValue($userUsername, $this->platform);
        self::assertSame('paquito@gmail.com', $dbValue);
    }

    public function testConvertToDatabaseValueWithNull(): void
    {
        $dbValue = $this->type->convertToDatabaseValue(null, $this->platform);
        self::assertNull($dbValue);
    }

    public function testConvertToDatabaseValueWithInvalidType(): void
    {
        $this->expectException(UserInvalidArgumentException::class);
        $this->type->convertToDatabaseValue('Invalid Type', $this->platform);
    }

    public function testRequiresSQLCommentHint(): void
    {
        self::assertTrue($this->type->requiresSQLCommentHint($this->platform));
    }
}
