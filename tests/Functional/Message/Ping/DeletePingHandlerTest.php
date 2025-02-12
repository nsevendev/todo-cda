<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional\Message\Ping;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Controller\Api\Ping\DeletePing;
use Tocda\Entity\Ping\Ping;
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
]
class DeletePingHandlerTest extends TocdaFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;

    private PingRepository $repository;

    private DeletePingHandler $handler;

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

    /**
     * @throws Exception
     */
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

        $this->handler = new DeletePingHandler($this->repository);
        $this->transport('othersync')->send(new DeletePingCommand($ping->id()->toString()));
        $this->flush();

        $this->transport('othersync')->queue()->assertNotEmpty();
        $this->transport('othersync')->queue()->assertCount(1);
        $this->transport('othersync')->process(1);
        $this->transport('othersync')->queue()->assertCount(0);
    }
}
