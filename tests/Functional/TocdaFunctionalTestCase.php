<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use ReflectionException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tocda\Fixtures\RecursiveEntityPersiste;

abstract class TocdaFunctionalTestCase extends WebTestCase
{
    /**
     * @template T of object
     *
     * @param T ...$entities
     *
     * @return T|array<T>
     *
     * @throws ReflectionException
     */
    protected function persistAndFlush(object ...$entities)
    {
        foreach ($entities as $entity) {
            $this->persist($entity);
        }

        $this->flush();

        return 1 === count($entities) ? $entities[0] : $entities;
    }

    /**
     * @template T of object
     *
     * @param T $entity
     *
     * @return T
     *
     * @throws ReflectionException
     */
    protected function persist(object $entity): object
    {
        $manager = $this->getRegistry()->getManagerForClass($entity::class);

        if (null === $manager) {
            throw new RuntimeException(sprintf('No manager found for class %s', $entity::class));
        }

        RecursiveEntityPersiste::persist($manager, $entity);

        return $entity;
    }

    /**
     * Flushes all entity managers.
     */
    protected function flush(): void
    {
        foreach ($this->getRegistry()->getManagers() as $manager) {
            $manager->flush();
        }
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        if (!$entityManager instanceof EntityManagerInterface) {
            throw new RuntimeException('EntityManagerInterface not found in container.');
        }

        return $entityManager;
    }

    private function getRegistry(): ManagerRegistry
    {
        $registry = self::getContainer()->get('doctrine');

        if (!$registry instanceof ManagerRegistry) {
            throw new RuntimeException('Doctrine registry is not available.');
        }

        return $registry;
    }
}
