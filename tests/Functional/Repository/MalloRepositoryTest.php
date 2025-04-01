<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;
use Tocda\Entity\Mallo\Mallo;
use Tocda\Repository\Mallo\MalloRepository;
use Tocda\Tests\Faker\Entity\Mallo\MalloFaker;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;

#[
    CoversClass(MalloRepository::class),
    CoversClass(Mallo::class),
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
     */
    public function testWeCanPersistAndFindMallo(): void
    {
        $mallo = MalloFaker::new();

        $this->persistAndFlush($mallo);

        /** @var Mallo|null $found */
        $found = $this->malloRepository->find($mallo->id()); // Appelle la méthode find de MalloRepository avec l'id de $mallo en paramètre

        self::assertNotNull($found, 'MalloEntity non trouvé en base alors qu’on vient de le créer');
        self::assertSame('Mallo', $found->firstname());
        self::assertSame('Zimmermann', $found->lastname());
        self::assertSame(67, $found->number());

    }

    public function testPersitAndFlushWithRepository(): void
    {
        $mallo = MalloFaker::new();

        $this->malloRepository->save($mallo);

        /** @var Mallo|null $found */
        $found = $this->malloRepository->find($mallo->id());

        self::assertNotNull($found, 'MalloEntity non trouvé en base alors qu’on vient de le créer');
        self::assertSame('Mallo', $found->firstname());
        self::assertSame('Zimmermann', $found->lastname());
        self::assertSame(67, $found->number());
    }

    public function testPersitAndFlushAndRemoveWithRepository(): void
    {
        $mallo = MalloFaker::new();

        $this->malloRepository->save($mallo); // save est une méthode de la propriété malloRepository de la classe dans la quelle on se trouve
        $this->malloRepository->remove((string) $mallo->id()); // On appelle la méthode remove de MalloRepository avec l'id de $mallo en paramètre

        /** @var Mallo|null $found */
        $found = $this->malloRepository->find($mallo->id()); // On appelle la méthode find de MalloRepository avec l'id de $mallo en paramètre

        self::assertNull($found, 'MalloEntity trouvé en base alors qu’on vient de le supprimer');
    }
}
