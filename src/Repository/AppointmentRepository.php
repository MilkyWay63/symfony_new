<?php

namespace App\Repository;

use App\Entity\Appointment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @extends ServiceEntityRepository<Appointment>
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    public function save(Appointment $appointment, bool $flush = true): void
    {
        $this->getEntityManager()->persist($appointment);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Pagerfanta $appointments
     */
    public function findUpcomingAppointments(): Pagerfanta
    {
        $query = $this->createQueryBuilder('a')
            ->join('a.timeSlot', 't')
            ->andWhere('t.startTime > :now')
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getResult();

        return new Pagerfanta(new QueryAdapter($query));
    }
}
