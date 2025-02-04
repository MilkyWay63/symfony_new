<?php

namespace App\Repository;

use App\Entity\Psychologist;
use App\Entity\TimeSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @extends ServiceEntityRepository<TimeSlot>
 */
class TimeSlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeSlot::class);
    }

    public function save(TimeSlot $timeSlot, bool $flush = true): void
    {
        $this->getEntityManager()->persist($timeSlot);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Pagerfanta $timeSlots
     */
    public function findPsychologistAvailableTimeSlots(Psychologist $psychologist): Pagerfanta
    {
       $query = $this->createQueryBuilder('t')
            ->andWhere('t.psychologist = :psychologist')
            ->andWhere('t.isBooked = false')
            ->setParameter('psychologist', $psychologist)
            ->getQuery()
        ;

        return new Pagerfanta(new QueryAdapter($query));
    }

    public function remove(TimeSlot $timeSlot): void
    {
        $this->getEntityManager()->remove($timeSlot);
        $this->getEntityManager()->flush();
    }
}
