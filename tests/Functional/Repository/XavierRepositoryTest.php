<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;
use Tocda\Entity\Xavier\Xavier;
use Tocda\Repository\Xavier\XavierRepository;
use Tocda\Tests\Faker\Entity\Xavier\XavierFaker;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;

#[
    CoversClass(XavierRepository::class),
    CoversClass(Xavier::class),
]
class XavierRepositoryTest extends TocdaFunctionalTestCase
{
    private XavierRepository $xavierRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $entityManager = self::getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        /** @var XavierRepository $repository */
        $repository = self::getContainer()->get(XavierRepository::class);
        $this->xavierRepository = $repository;
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
    public function testWeCanPersistAndFindXavier(): void
    {
        $xavier = XavierFaker::new();

        $this->persistAndFlush($xavier);

        /** @var Xavier|null $found */
        $found = $this->xavierRepository->find($xavier->id());

        self::assertNotNull($found, 'XavierEntity non trouvé en base alors qu’on vient de le créer');
        self::assertSame('John', $found->firstname());
        self::assertSame('Doe', $found->lastname());
        self::assertSame(0, $found->number());

    }

    public function testPersitAndFlushWithRepository(): void
    {
        $xavier = XavierFaker::new();

        $this->xavierRepository->save($xavier);

        /** @var Xavier|null $found */
        $found = $this->xavierRepository->find($xavier->id());

        self::assertNotNull($found, 'XavierEntity non trouvé en base alors qu’on vient de le créer');
        self::assertSame('John', $found->firstname());
        self::assertSame('Doe', $found->lastname());
        self::assertSame(0, $found->number());
    }
}
