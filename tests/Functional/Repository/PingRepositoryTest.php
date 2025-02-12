<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;
use Tocda\Entity\Ping\Ping;
use Tocda\Repository\Ping\PingRepository;
use Tocda\Tests\Faker\Entity\Ping\PingFaker;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;

#[
    CoversClass(PingRepository::class),
    CoversClass(Ping::class),
]
class PingRepositoryTest extends TocdaFunctionalTestCase
{
    private PingRepository $pingRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $entityManager = self::getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        /** @var PingRepository $repository */
        $repository = self::getContainer()->get(PingRepository::class);
        $this->pingRepository = $repository;
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
     */
    public function testWeCanPersistAndFindPing(): void
    {
        $ping = PingFaker::new();

        $this->persistAndFlush($ping);

        /** @var Ping|null $found */
        $found = $this->pingRepository->find($ping->id());

        self::assertNotNull($found, 'PingEntity non trouvé en base alors qu’on vient de le créer');
        self::assertSame(200, $found->status());
        self::assertSame('Le ping à réussi', $found->message());

    }

    public function testPersitAndFlushWithRepository(): void
    {
        $ping = PingFaker::new();

        $this->pingRepository->save($ping);

        /** @var Ping|null $found */
        $found = $this->pingRepository->find($ping->id());

        self::assertNotNull($found, 'PingEntity non trouvé en base alors qu’on vient de le créer');
        self::assertSame(200, $found->status());
        self::assertSame('Le ping à réussi', $found->message());
    }
}
