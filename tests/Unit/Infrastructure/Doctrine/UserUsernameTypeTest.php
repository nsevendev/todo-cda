<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\User\ValueObject\UserUsername;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\User\UserInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\Doctrine\Types\User\UserUsernameType;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[
    CoversClass(UserUsernameType::class),
    CoversClass(UserUsername::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(UserInvalidArgumentException::class),
    CoversClass(Error::class),
]
final class UserUsernameTypeTest extends TocdaUnitTestCase
{
    private UserUsernameType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_user_username')) {
            Type::addType('app_user_username', UserUsernameType::class);
        }

        $this->type = Type::getType('app_user_username');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_user_username', $this->type->getName());
    }

    public function testGetSQLDeclaration(): void
    {
        $column = ['length' => 25];
        $sql = $this->type->getSQLDeclaration($column, $this->platform);
        self::assertSame('VARCHAR(25)', $sql);
    }

    public function testConvertToPHPValueWithValidString(): void
    {

        $userUsername = $this->type->convertToPHPValue('paquito', $this->platform);
        self::assertInstanceOf(UserUsername::class, $userUsername);
        self::assertSame('paquito', $userUsername->value());
    }

    public function testConvertToPHPValueWithNull(): void
    {
        $userUsername = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($userUsername);
    }

    public function testConvertToPHPValueWithInvalidType(): void
    {
        $this->expectException(UserInvalidArgumentException::class);
        $this->type->convertToPHPValue(123, $this->platform);
    }

    public function testConvertToDatabaseValueWithValidUserUsername(): void
    {
        $userUsername = new UserUsername('paquito');
        $dbValue = $this->type->convertToDatabaseValue($userUsername, $this->platform);
        self::assertSame('paquito', $dbValue);
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

    // Add tests UserUsername
    public function testUserEmailFromValueTrimsString(): void
    {
        $email = UserUsername::fromValue('   paquito  ');
        self::assertSame('paquito', $email->value());
    }

    public function testUserEmailFromValueThrowsExceptionIfEmpty(): void
    {
        $this->expectException(UserInvalidArgumentException::class);
        UserUsername::fromValue('   ');
    }

    public function testUserEmailFromValueThrowsExceptionIfTooLong(): void
    {
        $this->expectException(UserInvalidArgumentException::class);
        $longEmail = str_repeat('a', 26).'paquito';
        UserUsername::fromValue($longEmail);
    }

    public function testUserEmailJsonSerialize(): void
    {
        $email = UserUsername::fromValue('paquito');
        self::assertSame('paquito', $email->jsonSerialize());
    }

    public function testUserEmailToString(): void
    {
        $email = UserUsername::fromValue('paquito');
        self::assertSame('paquito', (string) $email);
    }
}
