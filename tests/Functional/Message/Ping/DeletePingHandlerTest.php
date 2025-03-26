<?php

declare(strict_types=1);

namespace Functional\Message\Ping;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Uid\Uuid;
use Tocda\Controller\Api\Ping\DeletePing;
use Tocda\Entity\Ping\Dto\PingPublishDeletedDto;
use Tocda\Entity\Ping\Ping;
use Tocda\Entity\Ping\ValueObject\PingMessage;
use Tocda\Entity\Ping\ValueObject\PingStatus;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Ping\PingBadRequestException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\Doctrine\Types\Ping\PingMessageType;
use Tocda\Infrastructure\Doctrine\Types\Ping\PingStatusType;
use Tocda\Infrastructure\Mercure\MercurePublish;
use Tocda\Message\Command\Ping\DeletePingCommand;
use Tocda\Message\Command\Ping\DeletePingHandler;
use Tocda\Repository\Ping\PingRepository;
use Tocda\Tests\Faker\Entity\Ping\PingFaker;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(PingRepository::class),
    CoversClass(Ping::class),
    CoversClass(DeletePing::class),
    CoversClass(DeletePingCommand::class),
    CoversClass(DeletePingHandler::class),
    CoversClass(MercurePublish::class),
    CoversClass(PingPublishDeletedDto::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(PingBadRequestException::class),
    CoversClass(Error::class),
    CoversClass(PingMessage::class),
    CoversClass(PingMessageType::class),
    CoversClass(PingStatus::class),
    CoversClass(PingStatusType::class),
]
class DeletePingHandlerTest extends TocdaFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;

    private PingRepository $repository;

    private DeletePingHandler $handler;

    // private MercurePublish $mercurePublish;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();

        $this->repository = $this->entityManager->getRepository(Ping::class);
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

    public function testDeletePing(): void
    {
        $ping = PingFaker::new();

        $this->entityManager->persist($ping);
        $this->entityManager->flush();

        $bus = self::getContainer()->get('messenger.default_bus');
        $command = new DeletePingCommand($ping->id()->toString());
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
        $this->transport('othersync')->send(new DeletePingCommand($id));
        $this->transport('othersync')->process(1);
        $this->transport('othersync')->catchExceptions();
    }
}
