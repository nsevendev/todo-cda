<?php

namespace Tocda\Repository\Mallo;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tocda\Entity\Mallo\Mallo;

/**
 * @extends ServiceEntityRepository<Mallo>
 */
class MalloRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mallo::class);
    }

    public function save(Mallo $mallo): void
    {
        $this->getEntityManager()->persist($mallo);
        $this->getEntityManager()->flush();
    }

    public function remove(string $id): void
    {
        $mallo = $this->find($id);
        if (null !== $mallo) {
            $this->getEntityManager()->remove($mallo);
            $this->save($mallo);
        }
    }
}
