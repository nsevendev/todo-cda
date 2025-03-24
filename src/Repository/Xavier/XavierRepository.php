<?php

namespace Tocda\Repository\Xavier;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tocda\Entity\Xavier\Xavier;

/**
 * @extends ServiceEntityRepository<Xavier>
 */
class XavierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Xavier::class);
    }

    public function save(Xavier $xavier): void
    {
        $this->getEntityManager()->persist($xavier);
        $this->getEntityManager()->flush();
    }

    public function remove(string $id): void
    {
        $xavier = $this->find($id);
        if (null !== $xavier) {
            $this->getEntityManager()->remove($xavier);
            $this->save($xavier);
        }
    }
}
