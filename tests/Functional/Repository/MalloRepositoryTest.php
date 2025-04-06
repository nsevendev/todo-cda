<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;
use Tocda\Entity\Mallo\Mallo;
use Tocda\Entity\Mallo\ValueObject\MalloFirstname;
use Tocda\Entity\Mallo\ValueObject\MalloLastname;
use Tocda\Entity\Mallo\ValueObject\MalloNumber;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mallo\MalloInvalidArgumentException;
use Tocda\Infrastructure\Doctrine\Types\Mallo\MalloFirstnameType;
use Tocda\Infrastructure\Doctrine\Types\Mallo\MalloLastnameType;
use Tocda\Infrastructure\Doctrine\Types\Mallo\MalloNumberType;
use Tocda\Repository\Mallo\MalloRepository;
use Tocda\Tests\Faker\Entity\Mallo\MalloFaker;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;

#[
    CoversClass(MalloRepository::class),
    CoversClass(Mallo::class),
    CoversClass(MalloFirstname::class),
    CoversClass(MalloFirstnameType::class),
    CoversClass(MalloLastname::class),
    CoversClass(MalloLastnameType::class),
    CoversClass(MalloNumber::class),
    CoversClass(MalloNumberType::class),
]
class MalloRepositoryTest extends TocdaFunctionalTestCase
{
    private MalloRepository $malloRepository; // Type (MalloRepository) puis Propriété ($malloRepository)

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $entityManager = self::getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        /** @var MalloRepository $repository */
        $repository = self::getContainer()->get(MalloRepository::class);
        $this->malloRepository = $repository; // Attribue le repository de Mallo à la propriété $malloRepository
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
     * @throws MalloInvalidArgumentException
     */
    public function testWeCanPersistAndFindMallo(): void
    {
        $mallo = MalloFaker::new();

        $this->persistAndFlush($mallo);

        /** @var Mallo|null $found */
        $found = $this->malloRepository->find($mallo->id()); // Appelle la méthode find de MalloRepository avec l'id de $mallo en paramètre

        self::assertNotNull($found, 'MalloEntity non trouvé en base alors qu’on vient de le créer');
        self::assertSame('John', $found->firstname()->value());
        self::assertSame('Doe', $found->lastname()->value());
        self::assertSame(13, $found->number()->value());
    }

    public function testPersitAndFlushWithRepository(): void
    {
        $mallo = MalloFaker::new();

        $this->malloRepository->save($mallo);

        /** @var Mallo|null $found */
        $found = $this->malloRepository->find($mallo->id());

        self::assertNotNull($found, 'MalloEntity non trouvé en base alors qu’on vient de le créer');
        self::assertSame('John', $found->firstname()->value());
        self::assertSame('Doe', $found->lastname()->value());
        self::assertSame(13, $found->number()->value());
    }
}
