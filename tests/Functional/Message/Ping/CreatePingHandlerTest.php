<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional\Message\Ping;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\Ping\Dto\PingCreateDto;
use Tocda\Entity\Ping\Dto\PingDto;
use Tocda\Entity\Ping\Ping;
use Tocda\Entity\Ping\ValueObject\PingMessage;
use Tocda\Entity\Ping\ValueObject\PingStatus;
use Tocda\Infrastructure\Doctrine\Types\Ping\PingMessageType;
use Tocda\Infrastructure\Doctrine\Types\Ping\PingStatusType;
use Tocda\Infrastructure\Mercure\MercurePublish;
use Tocda\Message\Command\Ping\CreatePingCommand;
use Tocda\Message\Command\Ping\CreatePingHandler;
use Tocda\Repository\Ping\PingRepository;
use Tocda\Tests\Faker\Dto\Ping\PingCreateDtoFaker;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(PingRepository::class),
    CoversClass(Ping::class),
    CoversClass(CreatePingCommand::class),
    CoversClass(PingCreateDto::class),
    CoversClass(PingMessage::class),
    CoversClass(PingStatus::class),
    CoversClass(CreatePingHandler::class),
    CoversClass(MercurePublish::class),
    CoversClass(PingDto::class),
    CoversClass(PingMessageType::class),
    CoversClass(PingStatusType::class),
]
class CreatePingHandlerTest extends TocdaFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private PingRepository $repository;
    private CreatePingHandler $handler;

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

    /**
     * @throws Exception
     */
    public function testHandlerProcessesMessage(): void
    {
        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = PingCreateDtoFaker::new();
        $command = new CreatePingCommand($dto);
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $m = $this->transport('async')->queue()->messages();
        self::assertInstanceOf(CreatePingCommand::class, $m[0]);
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);
    }
}
