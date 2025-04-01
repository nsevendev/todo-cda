<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional\Message\Mallo;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Entity\Mallo\Dto\MalloCreateDto;
use Tocda\Entity\Mallo\Dto\MalloDto;
use Tocda\Entity\Mallo\Mallo;
use Tocda\Entity\Mallo\ValueObject\MalloFirstname;
use Tocda\Entity\Mallo\ValueObject\MalloLastname;
use Tocda\Entity\Mallo\ValueObject\MalloNumber;
use Tocda\Infrastructure\Mercure\MercurePublish;
use Tocda\Message\Command\Mallo\CreateMalloCommand;
use Tocda\Message\Command\Mallo\CreateMalloHandler;
use Tocda\Repository\Mallo\MalloRepository;
use Tocda\Tests\Faker\Dto\Mallo\MalloCreateDtoFaker;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(MalloRepository::class),
    CoversClass(Mallo::class),
    CoversClass(CreateMalloCommand::class),
    CoversClass(MalloCreateDto::class),
    CoversClass(MalloFirstname::class),
    CoversClass(MalloLastname::class),
    CoversClass(MalloNumber::class),
    CoversClass(CreateMalloHandler::class),
    CoversClass(MercurePublish::class),
    CoversClass(MalloDto::class)
]
class CreateMalloHandlerTest extends TocdaFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private MalloRepository $repository;
    private CreateMalloHandler $handler;

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

    /**
     * @throws Exception
     */
    public function testHandlerProcessesMessage(): void
    {
        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = MalloCreateDtoFaker::new();
        // $handler = new CreateMalloEntityHandler($this->repository);
        $command = new CreateMalloCommand($dto);
        // $handler($command);
        $bus->dispatch($command);
        $this->flush();

        // $mallo = $this->repository->findAll();

        $this->transport('async')->queue()->assertNotEmpty();
        $m = $this->transport('async')->queue()->messages();
        self::assertInstanceOf(CreateMalloCommand::class, $m[0]);
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);
    }
}
