<?php

namespace App\Repository;

use App\Entity\Psychologist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @extends ServiceEntityRepository<Psychologist>
 */
class PsychologistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Psychologist::class);
    }

    public function save(Psychologist $psychologist, bool $flush = true): void
    {
        $this->getEntityManager()->persist($psychologist);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Pagerfanta $psychologists
     */
    public function findAllPsychologists(): Pagerfanta
    {
       $query = $this->createQueryBuilder('p')
           ->getQuery();

        return new Pagerfanta(new QueryAdapter($query));
    }
}
