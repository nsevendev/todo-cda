<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;
use Tocda\Entity\User\User;
use Tocda\Entity\User\ValueObject\UserEmail;
use Tocda\Entity\User\ValueObject\UserPassword;
use Tocda\Entity\User\ValueObject\UserUsername;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\User\UserInvalidArgumentException;
use Tocda\Infrastructure\Doctrine\Types\User\UserEmailType;
use Tocda\Infrastructure\Doctrine\Types\User\UserPasswordType;
use Tocda\Infrastructure\Doctrine\Types\User\UserUsernameType;
use Tocda\Repository\User\UserRepository;
use Tocda\Tests\Faker\Entity\User\UserFaker;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;

#[
    CoversClass(UserRepository::class),
    CoversClass(User::class),
    CoversClass(UserEmail::class),
    CoversClass(UserEmailType::class),
    CoversClass(UserUsername::class),
    CoversClass(UserUsernameType::class),
    CoversClass(UserPassword::class),
    CoversClass(UserPasswordType::class),
]
class UserRepositoryTest extends TocdaFunctionalTestCase
{
    private UserRepository $userRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $entityManager = self::getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        /** @var UserRepository $repository */
        $repository = self::getContainer()->get(UserRepository::class);
        $this->userRepository = $repository;
    }

    /**
     * @throws Exception
     */
    protected function tearDown(): void
    {
        $conn = $this->getEntityManager()->getConnection();

        if ($conn->isTransactionActive()) {
            $conn->rollBack();
        }
    }

    /**
     * @throws ReflectionException
     * @throws UserInvalidArgumentException
     */
    public function testWeCanPersistAndfindUser(): void
    {
        $user = UserFaker::new();

        $this->persistAndFlush($user);

        /** @var User|null $found */
        $found = $this->userRepository->find($user->id());

        self::assertNotNull($found, 'User not found');
        self::assertSame('paquito', $found->username()->value());
        self::assertSame('paquito@gmail.com', $found->email()->value());
        self::assertSame('Paquito123?', $found->password()->value());
    }

    public function testPersitAndFlushWithRepository(): void
    {
        $user = UserFaker::new();

        $this->userRepository->save($user);

        /** @var User|null $found */
        $found = $this->userRepository->find($user->id());

        self::assertNotNull($found, 'User not found');
        self::assertSame('paquito', $found->username()->value());
        self::assertSame('paquito@gmail.com', $found->email()->value());
        self::assertSame('Paquito123?', $found->password()->value());
    }
}
