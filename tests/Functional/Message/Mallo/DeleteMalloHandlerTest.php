<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional\Message\Ping;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Uid\Uuid;
use Tocda\Controller\Api\Mallo\DeleteMallo;
use Tocda\Entity\Mallo\Dto\MalloPublishDeletedDto;
use Tocda\Entity\Mallo\Mallo;
use Tocda\Entity\Mallo\ValueObject\MalloFirstname;
use Tocda\Entity\Mallo\ValueObject\MalloLastname;
use Tocda\Entity\Mallo\ValueObject\MalloNumber;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mallo\MalloBadRequestException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\Doctrine\Types\Mallo\MalloFirstnameType;
use Tocda\Infrastructure\Doctrine\Types\Mallo\MalloLastnameType;
use Tocda\Infrastructure\Doctrine\Types\Mallo\MalloNumberType;
use Tocda\Infrastructure\Mercure\MercurePublish;
use Tocda\Message\Command\Mallo\DeleteMalloCommand;
use Tocda\Message\Command\Mallo\DeleteMalloHandler;
use Tocda\Repository\Mallo\MalloRepository;
use Tocda\Tests\Faker\Entity\Mallo\MalloFaker;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(MalloRepository::class),
    CoversClass(Mallo::class),
    CoversClass(DeleteMallo::class),
    CoversClass(DeleteMalloCommand::class),
    CoversClass(DeleteMalloHandler::class),
    CoversClass(MercurePublish::class),
    CoversClass(MalloPublishDeletedDto::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(MalloBadRequestException::class),
    CoversClass(Error::class),
    CoversClass(MalloFirstname::class),
    CoversClass(MalloFirstnameType::class),
    CoversClass(MalloLastname::class),
    CoversClass(MalloLastnameType::class),
    CoversClass(MalloNumber::class),
    CoversClass(MalloNumberType::class),
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

    public function testDoctrineConfiguration(): void
    {
        $connection = self::getEntityManager()->getConnection();
        self::assertTrue($connection->isConnected(), 'La connexion à la base de données est inactive');
    }

    public function testDeleteMallo(): void
    {
        $mallo = MalloFaker::new();

        $this->entityManager->persist($mallo);
        $this->entityManager->flush();

        $bus = self::getContainer()->get('messenger.default_bus');
        $command = new DeleteMalloCommand($mallo->id()->toString());
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
        $this->transport('othersync')->send(new DeleteMalloCommand($id));
        $this->transport('othersync')->process(1);
        $this->transport('othersync')->catchExceptions();
    }
}
