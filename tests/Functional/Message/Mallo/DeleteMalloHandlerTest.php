<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional\Message\Ping;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Controller\Api\Mallo\DeleteMallo as MalloDeleteMallo;
use Tocda\Entity\Mallo\Mallo;
use Tocda\Message\Command\Mallo\DeleteMalloCommand;
use Tocda\Message\Command\Mallo\DeleteMalloHandler;
use Tocda\Repository\Mallo\MalloRepository;
use Tocda\Tests\Faker\Entity\Mallo\MalloFaker;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(MalloRepository::class),
    CoversClass(Mallo::class),
    CoversClass(MalloDeleteMallo::class),
    CoversClass(DeleteMalloCommand::class),
    CoversClass(DeleteMalloHandler::class),
]
class DeleteMalloHandlerTest extends TocdaFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;

    private MalloRepository $repository;

    private DeleteMalloHandler $handler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();

        $this->repository = $this->entityManager->getRepository(Mallo::class);
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

    public function testDeleteMallo(): void
    {
        $ping = MalloFaker::new();

        $this->entityManager->persist($ping);
        $this->entityManager->flush();

        $this->handler = new DeleteMalloHandler($this->repository);
        $this->transport('othersync')->send(new DeleteMalloCommand($ping->id()->toString()));
        $this->flush();

        $this->transport('othersync')->queue()->assertNotEmpty();
        $this->transport('othersync')->queue()->assertCount(1);
        $this->transport('othersync')->process(1);
        $this->transport('othersync')->queue()->assertCount(0);
    }
}
