<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional\Message\User;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\User\Dto\UserCreateDto;
use Tocda\Entity\User\Dto\UserDto;
use Tocda\Entity\User\User;
use Tocda\Entity\User\ValueObject\UserEmail;
use Tocda\Entity\User\ValueObject\UserPassword;
use Tocda\Entity\User\ValueObject\UserUsername;
use Tocda\Infrastructure\Doctrine\Types\User\UserEmailType;
use Tocda\Infrastructure\Doctrine\Types\User\UserPasswordType;
use Tocda\Infrastructure\Doctrine\Types\User\UserUsernameType;
use Tocda\Infrastructure\Mercure\MercurePublish;
use Tocda\Message\Command\User\CreateUserCommand;
use Tocda\Message\Command\User\CreateUserHandler;
use Tocda\Repository\User\UserRepository;
use Tocda\Tests\Faker\Dto\User\UserCreateDtoFaker;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(UserRepository::class),
    CoversClass(User::class),
    CoversClass(CreateUserCommand::class),
    CoversClass(UserCreateDto::class),
    CoversClass(UserEmail::class),
    CoversClass(UserPassword::class),
    CoversClass(UserUsername::class),
    CoversClass(CreateUserHandler::class),
    CoversClass(MercurePublish::class),
    CoversClass(UserDto::class),
    CoversClass(UserEmailType::class),
    CoversClass(UserPasswordType::class),
    CoversClass(UserUsernameType::class),
]
class CreateUserHandlerTest extends TocdaFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private UserRepository $repository;
    private CreateUserHandler $handler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();

        $this->repository = $this->entityManager->getRepository(User::class);
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

    public function testDoctrineConfiguration(): void
    {
        $connection = self::getEntityManager()->getConnection();
        self::assertTrue($connection->isConnected(), 'La connexion à la base de données est inactive');
    }

    /**
     * @throws Exception
     */
    public function testHandlerProcessesMessage(): void
    {
        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = UserCreateDtoFaker::new();
        $command = new CreateUserCommand($dto);
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $m = $this->transport('async')->queue()->messages();
        self::assertInstanceOf(CreateUserCommand::class, $m[0]);
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);
    }
}
