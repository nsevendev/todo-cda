<?php

declare(strict_types=1);

namespace Functional\Message\User;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Uid\Uuid;
use Tocda\Controller\Api\User\DeleteUser;
use Tocda\Entity\User\Dto\UserPublishDeletedDto;
use Tocda\Entity\User\User;
use Tocda\Entity\User\ValueObject\UserEmail;
use Tocda\Entity\User\ValueObject\UserPassword;
use Tocda\Entity\User\ValueObject\UserUsername;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\User\UserBadRequestException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\Doctrine\Types\User\UserEmailType;
use Tocda\Infrastructure\Doctrine\Types\User\UserPasswordType;
use Tocda\Infrastructure\Doctrine\Types\User\UserUsernameType;
use Tocda\Infrastructure\Mercure\MercurePublish;
use Tocda\Message\Command\User\DeleteUserCommand;
use Tocda\Message\Command\User\DeleteUserHandler;
use Tocda\Repository\User\UserRepository;
use Tocda\Tests\Faker\Entity\User\UserFaker;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(UserRepository::class),
    CoversClass(User::class),
    CoversClass(DeleteUser::class),
    CoversClass(DeleteUserCommand::class),
    CoversClass(DeleteUserHandler::class),
    CoversClass(MercurePublish::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(UserPublishDeletedDto::class),
    CoversClass(UserBadRequestException::class),
    CoversClass(Error::class),
    CoversClass(UserEmail::class),
    CoversClass(UserEmailType::class),
    CoversClass(UserPassword::class),
    CoversClass(UserPasswordType::class),
    CoversClass(UserUsername::class),
    CoversClass(UserUsernameType::class),
]
class DeleteUserHandlerTest extends TocdaFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;

    private UserRepository $repository;

    private DeleteUserHandler $handler;

    // private MercurePublish $mercurePublish;

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

    public function testDeleteUser(): void
    {
        $user = UserFaker::new();

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $bus = self::getContainer()->get('messenger.default_bus');
        $command = new DeleteUserCommand($user->id()->toString());
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);
    }

    public function testDeletePingNotExist(): void
    {
        $this->expectException(HandlerFailedException::class);
        $id = Uuid::v7()->toString();
        $this->transport('othersync')->send(new DeleteUserCommand($id));
        $this->transport('othersync')->process(1);
        $this->transport('othersync')->catchExceptions();
    }
}
